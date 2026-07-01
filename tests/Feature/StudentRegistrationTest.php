<?php

namespace Tests\Feature;

use App\Mail\StudentRegistrationConfirmation;
use App\Models\ApExamSubject;
use App\Models\StudentRegistration;
use App\Models\User;
use Database\Seeders\ApExamSubjectSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class StudentRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_registration_can_be_submitted_and_email_is_sent(): void
    {
        Mail::fake();
        $this->seed(ApExamSubjectSeeder::class);
        $subject = ApExamSubject::query()->firstOrFail();

        $response = $this->post('/student-registration', $this->validPayload([
            'exam_subject_ids' => [$subject->id],
        ]));

        $registration = StudentRegistration::query()->with(['contact', 'exams', 'agreements', 'histories'])->firstOrFail();

        $response->assertRedirect(route('student-registrations.show', $registration->registration_number));
        $this->assertMatchesRegularExpression('/^APR-\d{4}-\d{6}$/', $registration->registration_number);
        $this->assertSame('submitted', $registration->status);
        $this->assertSame(7800, $registration->exam_fee_total);
        $this->assertSame(1200, $registration->service_fee_total);
        $this->assertSame(9000, $registration->total_fee);
        $this->assertCount(1, $registration->exams);
        $this->assertCount(4, $registration->agreements);
        $this->assertCount(1, $registration->histories);
        Mail::assertSent(StudentRegistrationConfirmation::class);
    }

    public function test_duplicate_email_and_passport_are_rejected(): void
    {
        Mail::fake();
        $this->seed(ApExamSubjectSeeder::class);
        $subject = ApExamSubject::query()->firstOrFail();
        $payload = $this->validPayload(['exam_subject_ids' => [$subject->id]]);

        $this->post('/student-registration', $payload)->assertRedirect();

        $this->post('/student-registration', $payload)
            ->assertSessionHasErrors(['student_email', 'passport_number']);
    }

    public function test_closed_exam_cannot_be_selected(): void
    {
        $subject = ApExamSubject::query()->create([
            'name' => 'Closed Subject',
            'code' => 'CLOSED',
            'exam_date' => '2027-05-01',
            'exam_fee' => 7800,
            'service_fee' => 1200,
            'status' => 'closed',
        ]);

        $this->post('/student-registration', $this->validPayload([
            'exam_subject_ids' => [$subject->id],
        ]))->assertSessionHasErrors(['exam_subject_ids.0']);
    }

    public function test_admin_can_filter_export_and_update_status(): void
    {
        Mail::fake();
        $this->seed(ApExamSubjectSeeder::class);
        $subject = ApExamSubject::query()->firstOrFail();
        $this->post('/student-registration', $this->validPayload(['exam_subject_ids' => [$subject->id]]));
        $registration = StudentRegistration::query()->firstOrFail();
        $this->actingAs($this->adminUser());

        $this->get('/admin/student-registrations?search=APR')
            ->assertOk()
            ->assertSee($registration->registration_number);

        $this->get('/admin/student-registrations/export?format=csv')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/csv; charset=utf-8');

        $this->put(route('admin.student-registrations.update', $registration), [
            'status' => 'pending_payment',
            'note' => 'Ready for payment.',
        ])->assertRedirect(route('admin.student-registrations.show', $registration));

        $this->assertSame('pending_payment', $registration->fresh()->status);
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

    public function test_student_registration_localization_is_available(): void
    {
        app()->setLocale('zh_TW');

        $this->assertSame('學生 AP 考試報名', __('student_registration.title'));
    }

    private function validPayload(array $overrides = []): array
    {
        return array_replace([
            'student_full_name' => 'Alex Chen',
            'preferred_name' => 'Alex',
            'gender' => 'Male',
            'date_of_birth' => '2009-01-15',
            'nationality' => 'Taiwan',
            'passport_number' => 'A12345678',
            'passport_expiry_date' => '2030-01-15',
            'student_email' => 'alex@example.com',
            'student_phone' => '+886 912 345 678',
            'school_name' => 'Taipei International School',
            'school_country' => 'Taiwan',
            'school_city' => 'Taipei',
            'grade_level' => '11',
            'graduation_year' => '2027',
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
        ], $overrides);
    }
}
