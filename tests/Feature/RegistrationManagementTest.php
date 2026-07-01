<?php

namespace Tests\Feature;

use App\Models\ApExamSubject;
use App\Models\StudentRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_search_and_filter_registration_list(): void
    {
        [$registration, $subject] = $this->registration();
        $this->actingAs($this->adminUser());

        $this->get('/admin/student-registrations?search=parent@example.com')
            ->assertOk()
            ->assertSee($registration->registration_number);

        $this->get('/admin/student-registrations?search=A123')
            ->assertOk()
            ->assertSee($registration->student_full_name);

        $this->get('/admin/student-registrations?payment_status=paid&period=late&subject_id='.$subject->id)
            ->assertOk()
            ->assertSee($registration->registration_number);
    }

    public function test_admin_can_view_detail_with_management_sections(): void
    {
        [$registration] = $this->registration();

        $this->actingAs($this->adminUser())
            ->get(route('admin.student-registrations.show', $registration))
            ->assertOk()
            ->assertSee('Student Information')
            ->assertSee('Internal Notes')
            ->assertSee('Activity Log')
            ->assertSee('Verification');
    }

    public function test_critical_edit_requires_reason_and_creates_audit_log(): void
    {
        [$registration] = $this->registration();
        $admin = $this->adminUser();

        $payload = $this->editPayload($registration, ['student_email' => 'new@example.com']);

        $this->actingAs($admin)
            ->patch(route('admin.student-registrations.manage-update', $registration), $payload)
            ->assertSessionHasErrors(['reason']);

        $this->actingAs($admin)
            ->patch(route('admin.student-registrations.manage-update', $registration), $payload + ['reason' => 'Parent requested email correction.'])
            ->assertRedirect(route('admin.student-registrations.show', $registration));

        $this->assertSame('new@example.com', $registration->fresh()->student_email);
        $this->assertDatabaseHas('registration_audit_logs', [
            'student_registration_id' => $registration->id,
            'field_name' => 'student_email',
            'reason' => 'Parent requested email correction.',
        ]);
    }

    public function test_paid_exam_selection_cannot_be_changed(): void
    {
        [$registration] = $this->registration(['payment_status' => 'paid']);
        $newSubject = $this->subject(['code' => 'BIO', 'name' => 'Biology']);

        $this->actingAs($this->adminUser())
            ->patch(route('admin.student-registrations.manage-update', $registration), $this->editPayload($registration, [
                'exam_subject_uuids' => [$newSubject->uuid],
                'reason' => 'Try changing paid exam.',
            ]))->assertSessionHasErrors(['exam_subject_uuids']);
    }

    public function test_admin_can_verify_registration_and_unverified_without_exams_fails(): void
    {
        [$registration] = $this->registration();
        $admin = $this->adminUser();

        $this->actingAs($admin)
            ->post(route('admin.student-registrations.verify', $registration), [
                'verification_status' => 'verified',
                'verification_note' => 'Reviewed.',
            ])->assertRedirect(route('admin.student-registrations.show', $registration));

        $this->assertSame('verified', $registration->fresh()->verification_status);

        $empty = $this->registration([], false)[0];
        $this->actingAs($admin)
            ->post(route('admin.student-registrations.verify', $empty), [
                'verification_status' => 'verified',
            ])->assertSessionHasErrors(['verification_status']);
    }

    public function test_admin_can_add_internal_note_and_note_is_not_public(): void
    {
        [$registration] = $this->registration();
        $admin = $this->adminUser();
        $note = '<script>alert("private")</script> Call parent.';

        $this->actingAs($admin)
            ->post(route('admin.student-registrations.notes.store', $registration), [
                'note_type' => 'issue',
                'note' => $note,
                'is_pinned' => '1',
            ])->assertRedirect(route('admin.student-registrations.show', $registration));

        $this->actingAs($admin)
            ->get(route('admin.student-registrations.show', $registration))
            ->assertSee('&lt;script&gt;alert(&quot;private&quot;)&lt;/script&gt;', false)
            ->assertDontSee($note, false);

        $this->get(route('student-registrations.show', $registration->registration_number))
            ->assertOk()
            ->assertDontSee('Call parent.');
    }

    public function test_non_admin_cannot_edit_registration(): void
    {
        [$registration] = $this->registration();
        $user = User::query()->create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => Hash::make('StrongPass!123'),
            'is_admin' => false,
        ]);

        $this->actingAs($user)
            ->get(route('admin.student-registrations.edit', $registration))
            ->assertForbidden();
    }

    private function registration(array $overrides = [], bool $withExam = true): array
    {
        $subject = $this->subject();
        $registration = StudentRegistration::query()->create(array_replace([
            'registration_number' => 'APR-2026-'.str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT),
            'status' => 'submitted',
            'registration_period' => 'late',
            'payment_status' => 'paid',
            'student_full_name' => 'Alex Chen',
            'date_of_birth' => '2009-01-15',
            'nationality' => 'Taiwan',
            'passport_number' => 'A12345678',
            'passport_upload_status' => 'uploaded',
            'student_email' => 'alex@example.com',
            'student_phone' => '+886 912 345 678',
            'school_name' => 'Taipei International School',
            'school_country' => 'Taiwan',
            'grade_level' => '11',
            'exam_fee_total' => 7800,
            'service_fee_total' => 1200,
            'late_fee_total' => 1500,
            'total_fee' => 10500,
            'submitted_at' => now(),
        ], $overrides));
        $registration->contact()->create([
            'parent_full_name' => 'Ivon Jou',
            'relationship' => 'Mother',
            'parent_email' => 'parent@example.com',
            'parent_phone' => '+886 987 654 321',
            'emergency_contact_name' => 'Mark Jou',
            'emergency_contact_phone' => '+886 988 111 222',
            'emergency_contact_relationship' => 'Father',
        ]);
        if ($withExam) {
            $registration->exams()->attach($subject->id, [
                'uuid' => (string) \Illuminate\Support\Str::uuid(),
                'subject_name' => $subject->name,
                'exam_date' => $subject->exam_date,
                'exam_fee' => 7800,
                'service_fee' => 1200,
                'late_fee_snapshot' => 1500,
                'total_amount_snapshot' => 10500,
                'currency_snapshot' => 'NTD',
                'selected_at' => now(),
                'status' => 'selected',
            ]);
        }

        return [$registration->fresh(['contact', 'exams']), $subject];
    }

    private function subject(array $overrides = []): ApExamSubject
    {
        $next = ApExamSubject::query()->count() + 1;

        return ApExamSubject::query()->create(array_replace([
            'name' => 'Calculus AB',
            'code' => 'CALAB'.$next,
            'category' => 'Mathematics',
            'exam_date' => '2027-05-04',
            'exam_fee' => 7800,
            'service_fee' => 1200,
            'late_registration_fee' => 1500,
            'currency' => 'NTD',
            'status' => 'open',
            'registration_open_at' => now()->subMonth(),
            'registration_close_at' => now()->addMonth(),
            'is_active' => true,
        ], $overrides));
    }

    private function editPayload(StudentRegistration $registration, array $overrides = []): array
    {
        return array_replace([
            'student_full_name' => $registration->student_full_name,
            'date_of_birth' => $registration->date_of_birth->format('Y-m-d'),
            'nationality' => $registration->nationality,
            'passport_number' => $registration->passport_number,
            'student_email' => $registration->student_email,
            'student_phone' => $registration->student_phone,
            'school_name' => $registration->school_name,
            'school_country' => $registration->school_country,
            'school_city' => $registration->school_city,
            'grade_level' => $registration->grade_level,
            'parent_full_name' => $registration->contact->parent_full_name,
            'relationship' => $registration->contact->relationship,
            'parent_email' => $registration->contact->parent_email,
            'parent_phone' => $registration->contact->parent_phone,
            'emergency_contact_name' => $registration->contact->emergency_contact_name,
            'emergency_contact_phone' => $registration->contact->emergency_contact_phone,
            'emergency_contact_relationship' => $registration->contact->emergency_contact_relationship,
            'status' => $registration->status,
            'payment_status' => $registration->payment_status,
            'payment_method' => $registration->payment_method,
            'payment_reference' => $registration->payment_reference,
            'payment_amount' => $registration->payment_amount,
        ], $overrides);
    }

    private function adminUser(): User
    {
        return User::query()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('StrongPass!123'),
            'is_admin' => true,
        ]);
    }
}
