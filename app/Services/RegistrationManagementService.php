<?php

namespace App\Services;

use App\Mail\RegistrationCompletedMail;
use App\Models\RegistrationAuditLog;
use App\Models\StudentRegistration;
use App\Repositories\StudentRegistrationRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class RegistrationManagementService
{
    public function __construct(private readonly StudentRegistrationRepository $repository)
    {
    }

    public function update(StudentRegistration $registration, array $data, int $adminId, ?string $ip): StudentRegistration
    {
        if (in_array($registration->payment_status, ['paid', 'refunded'], true) && isset($data['exam_subject_uuids'])) {
            throw ValidationException::withMessages([
                'exam_subject_uuids' => 'Paid financial exam data cannot be changed without elevated permission.',
            ]);
        }

        return DB::transaction(function () use ($registration, $data, $adminId, $ip): StudentRegistration {
            $reason = $data['reason'] ?? null;
            $original = $registration->replicate();
            $fields = collect($data)->only([
                'family_name_en', 'first_name_en', 'middle_initial', 'middle_name', 'chinese_legal_name',
                'student_full_name', 'preferred_name', 'date_of_birth', 'nationality', 'passport_number', 'student_email', 'student_phone',
                'school_name', 'school_country', 'school_city', 'grade_level', 'status', 'payment_status',
                'payment_method', 'payment_reference', 'payment_date', 'payment_amount',
                'needs_accommodations', 'ssd_code', 'accommodation_status', 'accommodations_payload',
            ])->all();

            $registration->fill($fields)->save();

            $registration->contact()->updateOrCreate([], collect($data)->only([
                'parent_first_name', 'parent_last_name',
                'parent_full_name', 'relationship', 'parent_email', 'parent_phone',
                'mailing_address', 'mailing_city', 'postal_code',
                'emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relationship',
            ])->all());

            foreach ($fields as $field => $newValue) {
                $oldValue = $original->{$field};
                if ((string) $oldValue !== (string) $newValue) {
                    $this->audit($registration, 'updated', $field, $oldValue, $newValue, $reason, $adminId, $ip);
                }
            }

            if (isset($data['exam_subject_uuids']) && ! in_array($registration->payment_status, ['paid', 'refunded'], true)) {
                $this->replaceExamSelection($registration, $data['exam_subject_uuids'], $reason, $adminId, $ip);
            }

            Log::info('Registration edited.', ['registration' => $registration->registration_number, 'admin_id' => $adminId]);

            return $registration->fresh(['contact', 'exams', 'adminNotes', 'auditLogs']);
        });
    }

    public function verify(StudentRegistration $registration, array $data, int $adminId, ?string $ip): StudentRegistration
    {
        if ($data['verification_status'] === 'verified') {
            if ($registration->passport_upload_status === 'missing') {
                throw ValidationException::withMessages(['verification_status' => 'Cannot verify registration with missing passport.']);
            }
            if ($registration->exams()->count() === 0) {
                throw ValidationException::withMessages(['verification_status' => 'Cannot verify registration without selected exams.']);
            }
        }

        $old = $registration->verification_status;
        $wasCompleted = $registration->status === 'completed';
        $registration->update([
            'verification_status' => $data['verification_status'],
            'verification_note' => $data['verification_note'] ?? null,
            'verified_by' => $adminId,
            'verified_at' => now(),
        ]);
        $this->audit($registration, 'verified', 'verification_status', $old, $data['verification_status'], $data['verification_note'] ?? null, $adminId, $ip);

        if ($data['verification_status'] === 'verified' && $registration->payment_status === 'paid' && ! $wasCompleted) {
            $oldStatus = $registration->status;
            $registration->update(['status' => 'completed']);
            $this->audit($registration, 'completed', 'status', $oldStatus, 'completed', 'Payment paid and registration verified.', $adminId, $ip);
            app(SecurityAuditService::class)->log('registration', 'registration_completed', 'Registration completed.', $registration, ['status' => $oldStatus], ['status' => 'completed']);
            Mail::to($registration->student_email)
                ->cc($registration->contact?->parent_email)
                ->send(new RegistrationCompletedMail($registration->fresh(['contact', 'exams'])));
        }

        Log::info('Registration verification updated.', ['registration' => $registration->registration_number, 'status' => $data['verification_status']]);

        return $registration->fresh(['verifier', 'auditLogs']);
    }

    public function addNote(StudentRegistration $registration, array $data, int $adminId, ?string $ip): void
    {
        $registration->adminNotes()->create([
            'note_type' => $data['note_type'],
            'note' => $data['note'],
            'is_pinned' => (bool) ($data['is_pinned'] ?? false),
            'created_by' => $adminId,
            'updated_by' => $adminId,
        ]);
        $this->audit($registration, 'note_added', null, null, $data['note_type'], null, $adminId, $ip);
        Log::info('Internal note added.', ['registration' => $registration->registration_number, 'admin_id' => $adminId]);
    }

    private function replaceExamSelection(StudentRegistration $registration, array $uuids, ?string $reason, int $adminId, ?string $ip): void
    {
        $subjects = $this->repository->subjectsByUuids($uuids);
        if ($subjects->count() !== count(array_unique($uuids)) || $subjects->contains(fn ($subject) => ! $subject->isSelectable())) {
            throw ValidationException::withMessages(['exam_subject_uuids' => 'One or more selected exams are unavailable.']);
        }

        $oldSubjectIds = $registration->exams()->pluck('ap_exam_subjects.id');
        \App\Models\ApExamSubject::query()
            ->whereIn('id', $oldSubjectIds)
            ->where('registered_count', '>', 0)
            ->decrement('registered_count');

        $registration->exams()->detach();
        $examTotal = 0;
        $serviceTotal = 0;
        $lateTotal = 0;
        foreach ($subjects as $subject) {
            $lateFee = $subject->lateFeeApplies() ? $subject->late_registration_fee : 0;
            $registration->exams()->attach($subject->id, [
                'uuid' => (string) \Illuminate\Support\Str::uuid(),
                'subject_name' => $subject->name,
                'exam_date' => $subject->exam_date,
                'exam_fee' => $subject->exam_fee,
                'service_fee' => $subject->service_fee,
                'late_fee_snapshot' => $lateFee,
                'total_amount_snapshot' => $subject->exam_fee + $subject->service_fee + $lateFee,
                'currency_snapshot' => $subject->currency,
                'selected_at' => now(),
                'status' => 'selected',
            ]);
            $examTotal += $subject->exam_fee;
            $serviceTotal += $subject->service_fee;
            $lateTotal += $lateFee;
            $subject->increment('registered_count');
        }

        $grandTotal = $examTotal + $serviceTotal + $lateTotal + (int) $registration->practice_exam_total;
        $registration->update([
            'exam_fee_total' => $examTotal,
            'service_fee_total' => $serviceTotal,
            'late_fee_total' => $lateTotal,
            'total_fee' => $grandTotal,
            'grand_total' => $grandTotal,
        ]);
        $registration->payments()
            ->whereNotIn('payment_status', ['paid', 'refunded'])
            ->latest()
            ->first()
            ?->update([
                'exam_fee_amount' => $examTotal,
                'service_fee_amount' => $serviceTotal,
                'late_fee_amount' => $lateTotal,
                'grand_total' => $grandTotal,
            ]);
        $this->audit($registration, 'updated', 'exam_subjects', null, $subjects->pluck('code')->join(','), $reason, $adminId, $ip);
    }

    private function audit(StudentRegistration $registration, string $action, ?string $field, mixed $old, mixed $new, ?string $reason, int $adminId, ?string $ip): void
    {
        RegistrationAuditLog::query()->create([
            'student_registration_id' => $registration->id,
            'action' => $action,
            'field_name' => $field,
            'old_value' => is_scalar($old) || $old === null ? (string) $old : json_encode($old),
            'new_value' => is_scalar($new) || $new === null ? (string) $new : json_encode($new),
            'reason' => $reason,
            'performed_by' => $adminId,
            'performed_ip' => $ip,
            'performed_at' => now(),
        ]);
    }
}
