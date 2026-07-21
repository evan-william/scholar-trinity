<?php

namespace Tests\Feature;

use App\Mail\StudentRegistrationConfirmation;
use App\Models\ApExamSubject;
use App\Models\StudentRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ExamPreferenceSelectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_exam_selection_list_shows_status_date_and_fee_breakdown(): void
    {
        $subject = $this->subject(['status' => 'limited', 'category' => 'Mathematics']);

        $this->get('/student-registration')
            ->assertOk()
            ->assertSee($subject->name)
            ->assertSee($subject->code)
            ->assertSee('Mathematics')
            ->assertSee('Limited Seats')
            ->assertSee('Exam NT$ 7,800')
            ->assertSee('Service NT$ 1,200');
    }

    public function test_uuid_selection_stores_late_fee_and_fee_snapshot(): void
    {
        Mail::fake();
        $subject = $this->subject([
            'late_registration_start_at' => now()->subDay(),
            'late_registration_end_at' => now()->addDay(),
            'late_registration_fee' => 1500,
        ]);

        $this->post('/student-registration', $this->payload([
            'exam_subject_uuids' => [$subject->uuid],
        ]))->assertRedirect();

        $registration = StudentRegistration::query()->with('exams')->firstOrFail();
        $this->assertSame(1500, $registration->late_fee_total);
        $this->assertSame(10500, $registration->total_fee);
        $this->assertSame(1500, (int) $registration->exams->first()->pivot->late_fee_snapshot);
        $this->assertSame(10500, (int) $registration->exams->first()->pivot->total_amount_snapshot);
        Mail::assertSent(StudentRegistrationConfirmation::class);
    }

    public function test_full_or_quota_reached_exam_cannot_be_selected(): void
    {
        $full = $this->subject(['status' => 'full']);
        $quotaReached = $this->subject(['code' => 'QUOTA', 'name' => 'Quota Subject', 'quota' => 1, 'registered_count' => 1]);

        $this->post('/student-registration', $this->payload(['exam_subject_uuids' => [$full->uuid]]))
            ->assertSessionHasErrors(['exam_subject_uuids']);

        $this->post('/student-registration', $this->payload([
            'student_email' => 'quota@example.com',
            'passport_number' => 'Q12345678',
            'exam_subject_uuids' => [$quotaReached->uuid],
        ]))->assertSessionHasErrors(['exam_subject_uuids']);
    }

    public function test_frontend_fee_tampering_is_ignored(): void
    {
        Mail::fake();
        $subject = $this->subject(['exam_fee' => 7800, 'service_fee' => 1200]);

        $payload = $this->payload([
            'exam_subject_uuids' => [$subject->uuid],
            'exam_fee_total' => 1,
            'total_fee' => 1,
        ]);

        $this->post('/student-registration', $payload)->assertRedirect();

        $this->assertSame(9000, StudentRegistration::query()->firstOrFail()->total_fee);
    }

    public function test_admin_can_create_update_and_delete_exam_subject(): void
    {
        $this->actingAs($this->adminUser());

        $payload = [
            'code' => 'ART',
            'name' => 'Art History',
            'category' => 'Arts',
            'description' => 'AP Art History',
            'exam_date' => '2027-05-20',
            'start_time' => '08:00',
            'end_time' => '12:00',
            'timezone' => 'Asia/Taipei',
            'location' => 'Room 101',
            'quota' => 20,
            'exam_fee' => 7800,
            'service_fee' => 1200,
            'late_registration_fee' => 1500,
            'currency' => 'NTD',
            'status' => 'open',
            'registration_open_at' => now()->subDay()->format('Y-m-d H:i:s'),
            'registration_close_at' => now()->addMonth()->format('Y-m-d H:i:s'),
            'late_registration_start_at' => now()->addWeek()->format('Y-m-d H:i:s'),
            'late_registration_end_at' => now()->addWeeks(2)->format('Y-m-d H:i:s'),
            'sort_order' => 99,
            'is_active' => '1',
        ];

        $this->post(route('admin.ap-exam-subjects.store'), $payload)
            ->assertRedirect(route('admin.ap-exam-subjects.index'));

        $subject = ApExamSubject::query()->where('code', 'ART')->firstOrFail();

        $this->put(route('admin.ap-exam-subjects.update', $subject), array_replace($payload, [
            'status' => 'limited',
            'service_fee' => 1300,
        ]))->assertRedirect(route('admin.ap-exam-subjects.index'));

        $this->assertSame('limited', $subject->fresh()->status);
        $this->assertSame(1300, $subject->fresh()->service_fee);

        $this->delete(route('admin.ap-exam-subjects.destroy', $subject))
            ->assertRedirect(route('admin.ap-exam-subjects.index'));

        $this->assertSoftDeleted('ap_exam_subjects', ['id' => $subject->id]);
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

    private function subject(array $overrides = []): ApExamSubject
    {
        return ApExamSubject::query()->create(array_replace([
            'name' => 'Calculus AB',
            'code' => 'CALAB',
            'category' => 'Mathematics',
            'exam_date' => '2027-05-04',
            'start_time' => '08:00',
            'end_time' => '12:00',
            'timezone' => 'Asia/Taipei',
            'location' => 'TPCA Campus',
            'quota' => 50,
            'registered_count' => 0,
            'exam_fee' => 7800,
            'service_fee' => 1200,
            'late_registration_fee' => 0,
            'currency' => 'NTD',
            'status' => 'open',
            'registration_open_at' => now()->subMonth(),
            'registration_close_at' => now()->addMonth(),
            'sort_order' => 1,
            'is_active' => true,
        ], $overrides));
    }

    private function payload(array $overrides = []): array
    {
        return array_replace([
            'student_full_name' => 'Alex Chen',
            'date_of_birth' => '2009-01-15',
            'nationality' => 'Taiwan',
            'passport_number' => 'A12345678',
            'student_email' => 'alex@example.com',
            'school_name' => 'Taipei International School',
            'school_country' => 'Taiwan',
            'grade_level' => '11',
            'parent_full_name' => 'Ivon Jou',
            'relationship' => 'Mother',
            'parent_email' => 'parent@example.com',
            'parent_phone' => '+886 987 654 321',
            'emergency_contact_name' => 'Mark Jou',
            'emergency_contact_phone' => '+886 988 111 222',
            'emergency_contact_relationship' => 'Father',
            'accurate_information' => '1',
            'ap_policies' => '1',
            'privacy_policy' => '1',
            'terms_conditions' => '1',
            'confirmed_review' => '1',
            'student_signature_name' => 'Alex Student',
            'student_signature_date' => now()->toDateString(),
            'guardian_signature_name' => 'Pat Parent',
            'guardian_signature_date' => now()->toDateString(),
        ], $overrides);
    }
}
