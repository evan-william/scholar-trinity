<?php

namespace Tests\Feature;

use App\Mail\StudentRegistrationConfirmation;
use App\Models\ApExamSubject;
use App\Models\StudentRegistration;
use App\Models\User;
use Database\Seeders\ApExamSubjectSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
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
        $this->assertSame('CHEN', $registration->family_name_en);
        $this->assertSame('Ming', $registration->first_name_en);
        $this->assertSame('Chen Ming Hua', $registration->chinese_legal_name);
        $this->assertTrue($registration->needs_accommodations);
        $this->assertSame('SSD-123', $registration->ssd_code);
        $this->assertSame(1, $registration->practice_exam_count);
        $this->assertSame(1800, $registration->practice_exam_total);
        $this->assertSame(7800, $registration->exam_fee_total);
        $this->assertSame(1200, $registration->service_fee_total);
        $this->assertSame(10800, $registration->total_fee);
        $this->assertCount(1, $registration->exams);
        $this->assertCount(1, $registration->practiceExamSelections);
        $this->assertCount(4, $registration->agreements);
        $this->assertCount(1, $registration->histories);
        Mail::assertSent(StudentRegistrationConfirmation::class);
    }

    public function test_duplicate_email_and_passport_are_rejected(): void
    {
        Mail::fake();
        $this->seed(ApExamSubjectSeeder::class);
        $subject = ApExamSubject::query()->firstOrFail();
        $payload = ['exam_subject_ids' => [$subject->id]];

        $this->post('/student-registration', $this->validPayload($payload))->assertRedirect();

        $this->post('/student-registration', $this->validPayload($payload))
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

    public function test_registration_validation_returns_to_relevant_step_and_preserves_passport_draft(): void
    {
        Mail::fake();
        Storage::fake('local');
        $this->seed(ApExamSubjectSeeder::class);
        $subject = ApExamSubject::query()->firstOrFail();

        $this->post('/student-registration', $this->validPayload([
            'exam_subject_ids' => [$subject->id],
        ]))->assertRedirect();

        $response = $this->post('/student-registration', $this->validPayload([
            'passport_number' => 'B12345678',
            'exam_subject_ids' => [$subject->id],
            'passport_file' => UploadedFile::fake()->image('passport.jpg'),
        ]));

        $response->assertSessionHasErrors(['student_email']);
        $response->assertSessionHas('student_registration_error_step', 1);

        $token = session('_old_input.passport_file_token');
        $this->assertIsString($token);
        $this->assertArrayHasKey($token, session('student_registration_passport_drafts'));

        $this->post('/student-registration', $this->validPayload([
            'student_email' => 'second@example.com',
            'passport_number' => 'B12345678',
            'exam_subject_ids' => [$subject->id],
            'passport_file_token' => $token,
        ]))->assertRedirect();

        $registration = StudentRegistration::query()
            ->where('student_email', 'second@example.com')
            ->firstOrFail();

        $this->assertSame('passport.jpg', $registration->passport_original_name);
        Storage::disk('local')->assertExists($registration->passport_file_path);
        $this->assertArrayNotHasKey($token, session('student_registration_passport_drafts', []));
    }

    public function test_registration_exam_errors_return_to_exam_step(): void
    {
        $response = $this->post('/student-registration', $this->validPayload([
            'exam_subject_ids' => [],
            'exam_subject_uuids' => [],
        ]));

        $response->assertSessionHasErrors(['exam_subject_uuids', 'exam_subject_ids']);
        $response->assertSessionHas('student_registration_error_step', 3);
    }

    public function test_registration_requires_passport_upload_or_saved_draft(): void
    {
        $this->seed(ApExamSubjectSeeder::class);
        $subject = ApExamSubject::query()->firstOrFail();
        $payload = $this->validPayload([
            'exam_subject_ids' => [$subject->id],
            'passport_file' => null,
            'passport_file_token' => null,
        ]);

        $this->post('/student-registration', $payload)
            ->assertSessionHasErrors(['passport_file']);
    }

    public function test_passport_draft_can_be_saved_before_final_submission(): void
    {
        Storage::fake('local');

        $response = $this->postJson(route('student-registrations.passport-draft'), [
            'passport_file' => UploadedFile::fake()->image('passport-refresh.jpg'),
        ]);

        $response->assertOk()
            ->assertJsonPath('name', 'passport-refresh.jpg');

        $token = $response->json('token');
        $this->assertIsString($token);
        $this->assertArrayHasKey($token, session('student_registration_passport_drafts'));
        Storage::disk('local')->assertExists(session("student_registration_passport_drafts.$token.path"));
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
            'family_name_en' => 'CHEN',
            'first_name_en' => 'Ming',
            'middle_initial' => 'A',
            'middle_name' => 'Alex',
            'chinese_legal_name' => 'Chen Ming Hua',
            'preferred_name' => 'Alex',
            'gender' => 'Male',
            'date_of_birth' => '2009-01-15',
            'nationality' => 'Taiwan',
            'passport_number' => 'A12345678',
            'passport_expiry_date' => '2030-01-15',
            'passport_file' => UploadedFile::fake()->image('passport.jpg'),
            'student_email' => 'alex@example.com',
            'student_phone' => '+886 912 345 678',
            'school_name' => 'Taipei International School',
            'school_country' => 'Taiwan',
            'school_city' => 'Taipei',
            'grade_level' => '11',
            'graduation_year' => '2027',
            'parent_full_name' => 'Ivon Jou',
            'parent_first_name' => 'Ivon',
            'parent_last_name' => 'Jou',
            'relationship' => 'Mother',
            'parent_email' => 'parent@example.com',
            'parent_phone' => '+886 987 654 321',
            'emergency_contact_name' => 'Mark Jou',
            'emergency_contact_phone' => '+886 988 111 222',
            'emergency_contact_relationship' => 'Father',
            'needs_accommodations' => '1',
            'ssd_code' => 'SSD-123',
            'accommodation_status' => 'approved',
            'accommodations' => [
                ['exam' => 'Calculus AB', 'request' => 'Extra time'],
            ],
            'practice_exams' => ['Calculus AB practice'],
            'practice_exam_total' => '99999',
            'accurate_information' => '1',
            'ap_policies' => '1',
            'privacy_policy' => '1',
            'terms_conditions' => '1',
            'confirmed_review' => '1',
        ], $overrides);
    }
}
