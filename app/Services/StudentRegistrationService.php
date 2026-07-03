<?php

namespace App\Services;

use App\Mail\StudentRegistrationConfirmation;
use App\Models\RegistrationExamSelection;
use App\Models\StudentRegistration;
use App\Repositories\StudentRegistrationRepository;
use App\Services\PaymentFlowService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class StudentRegistrationService
{
    public function __construct(private readonly StudentRegistrationRepository $repository)
    {
    }

    public function create(array $data, ?string $ipAddress = null, ?string $userAgent = null): StudentRegistration
    {
        $selectedKeys = $data['exam_subject_uuids'] ?? $data['exam_subject_ids'] ?? [];
        $examField = isset($data['exam_subject_uuids']) ? 'exam_subject_uuids' : 'exam_subject_ids';
        $subjects = isset($data['exam_subject_uuids'])
            ? $this->repository->subjectsByUuids($selectedKeys)
            : $this->repository->subjectsByIds($selectedKeys);

        if ($subjects->count() !== count(array_unique($selectedKeys))) {
            Log::warning('Student registration validation failure: unavailable subject selected.', [
                'student_email' => $data['student_email'] ?? null,
            ]);

            throw ValidationException::withMessages([
                $examField => __('student_registration.validation.exam_unavailable'),
                $examField.'.0' => __('student_registration.validation.exam_unavailable'),
            ]);
        }

        $blocked = $subjects->first(fn ($subject) => ! $subject->isSelectable());
        if ($blocked) {
            Log::warning('Student registration selection failed.', [
                'subject' => $blocked->code,
                'status' => $blocked->status,
                'registered_count' => $blocked->registered_count,
                'quota' => $blocked->quota,
            ]);

            throw ValidationException::withMessages([
                $examField => __('student_registration.validation.exam_closed'),
                $examField.'.0' => __('student_registration.validation.exam_closed'),
            ]);
        }

        return DB::transaction(function () use ($data, $subjects, $ipAddress, $userAgent): StudentRegistration {
            $season = $subjects->first(fn ($subject) => $subject->examSeason)?->examSeason;
            $period = $season?->currentPeriod() === 'late' ? 'late' : 'main';
            $examTotal = $subjects->sum('exam_fee');
            $serviceTotal = $subjects->sum('service_fee');
            $lateTotal = $subjects->sum(fn ($subject) => $subject->lateFeeApplies() ? $subject->late_registration_fee : 0);
            $practiceExams = $this->practiceExams($data['practice_exams'] ?? []);
            $practiceFee = (int) config('registration.practice_exam_fee', 1800);
            $practiceTotal = count($practiceExams) * $practiceFee;
            $accommodations = $this->accommodations($data['accommodations'] ?? []);
            $currency = $subjects->first()?->currency ?? 'NTD';
            $grandTotal = $examTotal + $serviceTotal + $lateTotal + $practiceTotal;

            $paymentMethod = $this->paymentMethod($data['payment_method'] ?? null);

            $registration = StudentRegistration::query()->create([
                'registration_number' => $this->makeRegistrationNumber(),
                'exam_season_id' => $season?->id,
                'status' => 'submitted',
                'family_name_en' => $data['family_name_en'] ?? null,
                'first_name_en' => $data['first_name_en'] ?? null,
                'middle_initial' => $data['middle_initial'] ?? null,
                'middle_name' => $data['middle_name'] ?? null,
                'chinese_legal_name' => $data['chinese_legal_name'] ?? null,
                'registration_period' => $period,
                'registration_period_type' => $period,
                'payment_method' => $paymentMethod,
                'student_full_name' => $data['student_full_name'],
                'preferred_name' => $data['preferred_name'] ?? null,
                'gender' => $data['gender'] ?? null,
                'date_of_birth' => $data['date_of_birth'],
                'nationality' => $data['nationality'],
                'passport_number' => strtoupper($data['passport_number']),
                'passport_expiry_date' => $data['passport_expiry_date'] ?? null,
                'student_email' => $data['student_email'],
                'student_phone' => $data['student_phone'] ?? null,
                'school_name' => $data['school_name'],
                'school_country' => $data['school_country'],
                'school_city' => $data['school_city'] ?? null,
                'grade_level' => $data['grade_level'],
                'graduation_year' => $data['graduation_year'] ?? null,
                'exam_fee_total' => $examTotal,
                'service_fee_total' => $serviceTotal,
                'late_fee_total' => $lateTotal,
                'total_fee' => $grandTotal,
                'grand_total' => $grandTotal,
                'currency' => $currency,
                'fee_snapshot_at' => now(),
                'needs_accommodations' => (bool) ($data['needs_accommodations'] ?? false),
                'ssd_code' => $data['ssd_code'] ?? null,
                'accommodation_status' => $data['accommodation_status'] ?? null,
                'accommodations_payload' => $accommodations,
                'practice_exam_count' => count($practiceExams),
                'practice_exam_total' => $practiceTotal,
                'review_confirmed_at' => now(),
                'submitted_at' => now(),
            ]);

            if (isset($data['passport_file'])) {
                $file = $data['passport_file'];
                app(FileSecurityService::class)->validate($file, 'passport_file');
                $path = $file->store('student-passports', 'local');
                $registration->update([
                    'passport_upload_status' => 'pending_review',
                    'passport_document_uuid' => (string) Str::uuid(),
                    'passport_file_path' => $path,
                    'passport_original_name' => basename($file->getClientOriginalName()),
                    'passport_mime_type' => $file->getMimeType(),
                    'passport_file_size' => $file->getSize(),
                    'passport_uploaded_at' => now(),
                ]);
            } elseif (! empty($data['passport_file_token'])) {
                $draft = $this->consumePassportDraft((string) $data['passport_file_token']);

                if ($draft) {
                    $registration->update([
                        'passport_upload_status' => 'pending_review',
                        'passport_document_uuid' => (string) Str::uuid(),
                        'passport_file_path' => $draft['path'],
                        'passport_original_name' => $draft['name'],
                        'passport_mime_type' => $draft['mime'],
                        'passport_file_size' => $draft['size'],
                        'passport_uploaded_at' => now(),
                    ]);
                }
            }

            $registration->contact()->create([
                'parent_first_name' => $data['parent_first_name'] ?? null,
                'parent_last_name' => $data['parent_last_name'] ?? null,
                'parent_full_name' => $data['parent_full_name'],
                'relationship' => $data['relationship'],
                'parent_email' => $data['parent_email'],
                'parent_phone' => $data['parent_phone'],
                'mailing_address' => $data['mailing_address'] ?? null,
                'mailing_city' => $data['mailing_city'] ?? null,
                'postal_code' => $data['postal_code'] ?? null,
                'emergency_contact_name' => $data['emergency_contact_name'],
                'emergency_contact_phone' => $data['emergency_contact_phone'],
                'emergency_contact_relationship' => $data['emergency_contact_relationship'],
            ]);

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
                $subject->increment('registered_count');
            }

            foreach ($practiceExams as $practiceExam) {
                RegistrationExamSelection::query()->create([
                    'uuid' => (string) Str::uuid(),
                    'student_registration_id' => $registration->id,
                    'ap_exam_subject_id' => null,
                    'selection_type' => 'practice',
                    'exam_name' => $practiceExam,
                    'practice_fee' => $practiceFee,
                    'total_amount' => $practiceFee,
                    'currency' => $currency,
                    'status' => 'selected',
                    'metadata' => json_encode(['source' => 'student_registration_form']),
                    'selected_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            Log::info('Student registration total calculated.', [
                'registration_number' => $registration->registration_number,
                'exam_fee_total' => $examTotal,
                'service_fee_total' => $serviceTotal,
                'late_fee_total' => $lateTotal,
                'practice_exam_total' => $practiceTotal,
            ]);

            foreach (['accurate_information', 'ap_policies', 'privacy_policy', 'terms_conditions'] as $agreement) {
                $registration->agreements()->create([
                    'agreement_key' => $agreement,
                    'accepted_at' => now(),
                    'ip_address' => $ipAddress,
                    'user_agent' => $userAgent,
                ]);
            }

            $registration->histories()->create([
                'from_status' => null,
                'to_status' => 'submitted',
                'note' => 'Registration submitted by student or parent.',
            ]);

            Log::info('Student registration submitted.', [
                'registration_number' => $registration->registration_number,
                'student_email' => $registration->student_email,
            ]);

            Mail::to($registration->student_email)
                ->cc($registration->contact->parent_email)
                ->send(new StudentRegistrationConfirmation($registration->load(['contact', 'exams'])));

            $registration->update(['confirmation_sent_at' => now()]);

            app(PaymentFlowService::class)->ensurePayment($registration->fresh(['contact', 'exams']), $paymentMethod);

            Log::info('Student registration confirmation email sent.', [
                'registration_number' => $registration->registration_number,
            ]);

            return $registration->load(['contact', 'exams', 'histories']);
        });
    }

    public function updateStatus(StudentRegistration $registration, string $status, ?string $note = null): StudentRegistration
    {
        return DB::transaction(function () use ($registration, $status, $note): StudentRegistration {
            $from = $registration->status;
            $registration->update(['status' => $status]);
            $registration->histories()->create([
                'from_status' => $from,
                'to_status' => $status,
                'note' => $note,
            ]);

            Log::info('Student registration status updated.', [
                'registration_number' => $registration->registration_number,
                'from' => $from,
                'to' => $status,
            ]);

            return $registration->fresh(['contact', 'exams', 'histories']);
        });
    }

    private function makeRegistrationNumber(): string
    {
        $prefix = config('registration.number_prefix', 'APR');
        $year = now()->year;
        $sequence = $this->repository->nextSequenceNumber();

        do {
            $number = sprintf('%s-%s-%06d', $prefix, $year, $sequence);
            $sequence++;
        } while (StudentRegistration::withTrashed()->where('registration_number', $number)->exists());

        return $number;
    }

    private function paymentMethod(?string $method): string
    {
        return match ($method) {
            'cash' => 'cash',
            'atm' => 'atm',
            'online', 'credit_card' => 'credit_card',
            default => 'manual_bank_transfer',
        };
    }

    /**
     * @return array{path: string, name: string, mime: string|null, size: int|null}|null
     */
    private function consumePassportDraft(string $token): ?array
    {
        $sessionKey = 'student_registration_passport_drafts';
        $drafts = session($sessionKey, []);
        $draft = $drafts[$token] ?? null;

        if (! is_array($draft) || empty($draft['path']) || ! Storage::disk('local')->exists($draft['path'])) {
            return null;
        }

        $extension = pathinfo((string) ($draft['name'] ?? $draft['path']), PATHINFO_EXTENSION) ?: 'upload';
        $path = 'student-passports/'.Str::uuid().'.'.$extension;

        Storage::disk('local')->move($draft['path'], $path);

        unset($drafts[$token]);
        session()->put($sessionKey, $drafts);

        return [
            'path' => $path,
            'name' => (string) ($draft['name'] ?? basename($path)),
            'mime' => $draft['mime'] ?? null,
            'size' => isset($draft['size']) ? (int) $draft['size'] : Storage::disk('local')->size($path),
        ];
    }

    /**
     * @param array<int, mixed> $practiceExams
     * @return array<int, string>
     */
    private function practiceExams(array $practiceExams): array
    {
        return collect($practiceExams)
            ->filter(fn ($value) => is_string($value) && trim($value) !== '')
            ->map(fn (string $value) => trim($value))
            ->unique()
            ->values()
            ->all();
    }

    /**
     * @param array<int, array<string, mixed>> $rows
     * @return array<int, array{exam: string, request: string}>
     */
    private function accommodations(array $rows): array
    {
        return collect($rows)
            ->map(fn (array $row) => [
                'exam' => trim((string) ($row['exam'] ?? '')),
                'request' => trim((string) ($row['request'] ?? '')),
            ])
            ->filter(fn (array $row) => $row['exam'] !== '' || $row['request'] !== '')
            ->values()
            ->all();
    }
}
