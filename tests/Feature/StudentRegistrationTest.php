<?php

namespace Tests\Feature;

use App\Mail\StudentRegistrationConfirmation;
use App\Models\ApExamSubject;
use App\Models\PracticeExamOption;
use App\Models\RegistrationPricingTier;
use App\Models\StudentRegistration;
use App\Models\User;
use Database\Seeders\ApExamSubjectSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Tests\TestCase;

class StudentRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ThrottleRequests::class);
    }

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
        $practiceFee = (int) config('registration.practice_exam_fee', 2800);
        $this->assertSame($practiceFee, $registration->practice_exam_total);
        $this->assertSame(7800, $registration->exam_fee_total);
        $this->assertSame(9700, $registration->service_fee_total);
        $this->assertSame(17500 + $practiceFee, $registration->total_fee);
        $this->assertSame('Alex Student', $registration->student_signature_name);
        $this->assertSame(now()->toDateString(), $registration->student_signature_date->toDateString());
        $this->assertSame('Pat Parent', $registration->guardian_signature_name);
        $this->assertSame(now()->toDateString(), $registration->guardian_signature_date->toDateString());
        $this->assertCount(1, $registration->exams);
        $this->assertCount(1, $registration->practiceExamSelections);
        $this->assertCount(4, $registration->agreements);
        $this->assertCount(1, $registration->histories);
        Mail::assertSent(StudentRegistrationConfirmation::class);
    }

    public function test_public_confirmation_is_available_once_after_submission(): void
    {
        Mail::fake();
        $this->seed(ApExamSubjectSeeder::class);
        $subject = ApExamSubject::query()->firstOrFail();

        $response = $this->post('/student-registration', $this->validPayload([
            'exam_subject_ids' => [$subject->id],
        ]));

        $registration = StudentRegistration::query()->firstOrFail();
        $confirmationUrl = route('student-registrations.show', $registration->registration_number);

        $response->assertRedirect($confirmationUrl);

        $this->get($confirmationUrl)
            ->assertOk()
            ->assertSee($registration->registration_number)
            ->assertSee($registration->student_full_name)
            ->assertDontSee($registration->passport_number);

        $this->get($confirmationUrl)
            ->assertRedirect(route('landing'))
            ->assertSessionHas('status', __('student_registration.confirmation_expired'));
    }

    public function test_public_confirmation_cannot_be_opened_without_submission_session(): void
    {
        Mail::fake();
        $this->seed(ApExamSubjectSeeder::class);
        $subject = ApExamSubject::query()->firstOrFail();

        $this->post('/student-registration', $this->validPayload([
            'exam_subject_ids' => [$subject->id],
        ]));

        $registration = StudentRegistration::query()->firstOrFail();
        $this->flushSession();

        $this->get(route('student-registrations.show', $registration->registration_number))
            ->assertRedirect(route('landing'))
            ->assertSessionHas('status', __('student_registration.confirmation_expired'));
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

    public function test_student_information_step_rejects_duplicate_identity_before_progressing(): void
    {
        Mail::fake();
        Storage::fake('local');
        $this->seed(ApExamSubjectSeeder::class);
        $subject = ApExamSubject::query()->firstOrFail();

        $this->post('/student-registration', $this->validPayload([
            'exam_subject_ids' => [$subject->id],
        ]))->assertRedirect();

        $draft = $this->postJson(route('student-registrations.passport-draft'), [
            'passport_file' => UploadedFile::fake()->image('duplicate-passport.jpg'),
        ])->assertOk();

        $this->postJson(route('student-registrations.validate-step'), [
            'step' => 1,
            'family_name_en' => 'CHEN',
            'first_name_en' => 'Ming',
            'chinese_legal_name' => 'Chen Ming Hua',
            'date_of_birth' => '2009-01-15',
            'nationality' => 'Taiwan',
            'passport_number' => 'A12345678',
            'passport_expiry_date' => '2030-01-15',
            'grade' => '11',
            'current_school' => 'Taipei International School',
            'student_email' => 'alex@example.com',
            'student_phone' => '+886 912 345 678',
            'passport_file_token' => $draft->json('token'),
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['passport_number', 'student_email']);
    }

    public function test_guardian_step_rejects_missing_required_fields_before_progressing(): void
    {
        $this->postJson(route('student-registrations.validate-step'), [
            'step' => 2,
        ])->assertUnprocessable()
            ->assertJsonValidationErrors([
                'parent_first_name',
                'parent_last_name',
                'relationship',
                'parent_email',
                'parent_phone',
                'mailing_address',
                'mailing_city',
                'emergency_contact_name',
                'emergency_contact_phone',
                'emergency_contact_relationship',
            ]);
    }

    public function test_registration_remains_visible_when_email_delivery_fails(): void
    {
        $this->seed(ApExamSubjectSeeder::class);
        $subject = ApExamSubject::query()->firstOrFail();
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('SMTP unavailable'));

        $this->post('/student-registration', $this->validPayload([
            'exam_subject_ids' => [$subject->id],
        ]))->assertRedirect();

        $registration = StudentRegistration::query()->firstOrFail();

        $this->assertSame('submitted', $registration->status);
        $this->assertNull($registration->confirmation_sent_at);
        $this->assertDatabaseHas('registration_payments', [
            'student_registration_id' => $registration->id,
            'payment_status' => 'pending',
        ]);
        $this->assertDatabaseHas('receipt_requests', [
            'student_registration_id' => $registration->id,
            'status' => 'not_requested',
        ]);

        $this->actingAs($this->adminUser())
            ->get(route('admin.student-registrations.index'))
            ->assertOk()
            ->assertSee($registration->registration_number);
    }

    public function test_registration_uses_active_practice_exam_options_and_saves_preparation_interest(): void
    {
        Mail::fake();
        $this->seed(ApExamSubjectSeeder::class);
        $subject = ApExamSubject::query()->firstOrFail();
        $practice = PracticeExamOption::query()->create([
            'name' => 'AP Calculus AB Mock Exam',
            'category' => 'Mathematics',
            'practice_date' => '2027-03-15',
            'fee' => 2500,
            'seat_capacity' => 20,
            'currency' => 'NTD',
            'is_active' => true,
        ]);

        $this->post('/student-registration', $this->validPayload([
            'exam_subject_ids' => [$subject->id],
            'practice_exams' => [$practice->uuid],
            'preparation_interest' => '1',
            'primacy_email_opt_in' => '1',
            'group_class_interest' => '1',
            'private_tutoring_interest' => '1',
            'preferred_tutoring_schedule' => 'Weekend morning',
            'preferred_tutoring_language' => 'English',
            'preparation_notes' => 'Needs calculus review.',
        ]))->assertRedirect();

        $registration = StudentRegistration::query()->with(['practiceExamSelections', 'agreements'])->firstOrFail();
        $this->assertSame(1, $registration->practice_exam_count);
        $this->assertSame(2500, $registration->practice_exam_total);
        $this->assertSame(20000, $registration->total_fee);
        $this->assertTrue($registration->preparation_interest);
        $this->assertTrue($registration->primacy_email_opt_in);
        $this->assertTrue($registration->group_class_interest);
        $this->assertTrue($registration->private_tutoring_interest);
        $this->assertSame('Weekend morning', $registration->preferred_tutoring_schedule);
        $this->assertSame('English', $registration->preferred_tutoring_language);
        $this->assertSame('Needs calculus review.', $registration->preparation_notes);
        $this->assertSame('AP Calculus AB Mock Exam', $registration->practiceExamSelections->first()->exam_name);
        $this->assertSame(2500, $registration->practiceExamSelections->first()->practice_fee);
        $this->assertSame($practice->id, $registration->practiceExamSelections->first()->practice_exam_option_id);
        $this->assertTrue($registration->agreements->contains('agreement_key', 'primacy_email_marketing'));
    }

    public function test_practice_exam_cannot_be_selected_after_seat_capacity_is_reached(): void
    {
        Mail::fake();
        $this->seed(ApExamSubjectSeeder::class);
        $subject = ApExamSubject::query()->firstOrFail();
        $practice = PracticeExamOption::query()->create([
            'name' => 'Capacity Limited Mock Exam',
            'fee' => 2800,
            'seat_capacity' => 1,
            'currency' => 'NTD',
            'is_active' => true,
        ]);

        $this->post('/student-registration', $this->validPayload([
            'exam_subject_ids' => [$subject->id],
            'practice_exams' => [$practice->uuid],
        ]))->assertRedirect();

        $this->post('/student-registration', $this->validPayload([
            'student_email' => 'second-student@example.com',
            'parent_email' => 'second-parent@example.com',
            'passport_number' => 'B98765432',
            'exam_subject_ids' => [$subject->id],
            'practice_exams' => [$practice->uuid],
        ]))->assertSessionHasErrors(['practice_exams']);

        $this->assertSame(1, $practice->selections()->count());
    }

    public function test_admin_can_set_practice_exam_fee_and_seat_capacity(): void
    {
        $this->actingAs($this->adminUser())
            ->post(route('admin.practice-exams.store'), [
                'name' => 'Biology Practice Exam',
                'category' => 'Sciences',
                'practice_date' => '2027-04-10',
                'start_time' => '09:00',
                'location' => 'Room 201',
                'fee' => 2800,
                'seat_capacity' => 32,
                'currency' => 'NTD',
                'sort_order' => 1,
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.practice-exams.index'));

        $this->assertDatabaseHas('practice_exam_options', [
            'name' => 'Biology Practice Exam',
            'fee' => 2800,
            'seat_capacity' => 32,
            'end_time' => null,
        ]);
    }

    public function test_admin_can_remove_practice_exam_without_erasing_history(): void
    {
        $practice = PracticeExamOption::query()->create([
            'name' => 'Removable Practice Exam',
            'fee' => 2500,
            'currency' => 'NTD',
            'is_active' => true,
        ]);

        $this->actingAs($this->adminUser())
            ->delete(route('admin.practice-exams.destroy', $practice))
            ->assertRedirect(route('admin.practice-exams.index'));

        $this->assertSoftDeleted('practice_exam_options', ['id' => $practice->id]);
        $this->assertFalse(PracticeExamOption::withTrashed()->findOrFail($practice->id)->is_active);
    }

    public function test_admin_can_update_unified_registration_pricing(): void
    {
        $this->actingAs($this->adminUser())
            ->put(route('admin.payments.pricing.update'), [
                'tiers' => [[
                'exam_count' => 2,
                'reference_usd_per_exam' => 460,
                'combined_fee_per_exam' => 16000,
                'exam_cost_total' => 15600,
                'currency' => 'NTD',
                    'is_active' => '1',
                ]],
            ])
            ->assertRedirect(route('admin.payments.settings'))
            ->assertSessionHasNoErrors();

        $tier = RegistrationPricingTier::query()->where('exam_count', 2)->firstOrFail();
        $this->assertSame(460, $tier->reference_usd_per_exam);
        $this->assertSame(8200, $tier->service_fee_per_exam);
    }

    public function test_registration_form_shows_five_explicit_required_acknowledgements(): void
    {
        $response = $this->get(route('student-registrations.create'));

        $response->assertOk();

        foreach (['accurate_information', 'terms_conditions', 'ap_policies', 'privacy_policy', 'confirmed_review'] as $field) {
            $response->assertSee('type="checkbox" name="'.$field.'"', false);
        }
    }

    public function test_registration_form_shows_localized_primacy_email_opt_in_and_pricing_note(): void
    {
        $english = $this->get(route('student-registrations.create'));

        $english->assertOk()
            ->assertSee('name="primacy_email_opt_in"', false)
            ->assertSee('I would like to receive education trends, exam information, and college application news by email from Primacy. I understand that I may unsubscribe at any time.')
            ->assertSee('The final price includes the exam fee, service fee, and any applicable late fees.');

        $traditionalChinese = $this->withSession(['locale' => 'zh-TW'])
            ->get(route('student-registrations.create'));

        $traditionalChinese->assertOk()
            ->assertSee('我願意透過電子郵件接收 Primacy 提供的留學考試、大學申請、留學趨勢等資訊，並了解可隨時取消訂閱。')
            ->assertSee('最終價格包含考試費用、代辦手續費，以及任何適用的逾期費用。');
    }

    public function test_review_step_requires_every_acknowledgement(): void
    {
        $this->postJson(route('student-registrations.validate-step'), [
            'step' => 5,
            'payment_method' => 'bank_transfer',
            'student_signature_name' => 'Alex Student',
            'student_signature_date' => now()->toDateString(),
            'guardian_signature_name' => 'Pat Parent',
            'guardian_signature_date' => now()->toDateString(),
        ])->assertStatus(422)->assertJsonValidationErrors([
            'accurate_information',
            'terms_conditions',
            'ap_policies',
            'privacy_policy',
            'confirmed_review',
        ]);
    }

    public function test_default_unified_pricing_matches_client_table_for_one_through_ten_exams(): void
    {
        $expectedPerExam = [17500, 16700, 15900, 15100, 14300, 13500, 13500, 13500, 13500, 13500];
        $expectedUsd = [500, 475, 450, 425, 400, 375, 375, 375, 375, 375];
        $tiers = RegistrationPricingTier::query()->orderBy('exam_count')->get();

        $this->assertCount(10, $tiers);

        foreach ($tiers as $index => $tier) {
            $this->assertSame($index + 1, $tier->exam_count);
            $this->assertSame($expectedUsd[$index], $tier->reference_usd_per_exam);
            $this->assertSame($expectedPerExam[$index], $tier->combined_fee_per_exam);
            $this->assertSame(7800, $tier->exam_fee_per_exam);
            $this->assertSame($expectedPerExam[$index] - 7800, $tier->service_fee_per_exam);
            $this->assertSame('NTD', $tier->currency);
            $this->assertTrue($tier->is_active);
        }
    }

    public function test_two_exam_registration_uses_volume_pricing_tier(): void
    {
        Mail::fake();
        $this->seed(ApExamSubjectSeeder::class);
        $subjects = ApExamSubject::query()->take(2)->get();

        $this->post('/student-registration', $this->validPayload([
            'exam_subject_ids' => $subjects->pluck('id')->all(),
            'practice_exams' => [],
        ]))->assertRedirect();

        $registration = StudentRegistration::query()->with('exams')->firstOrFail();
        $this->assertSame(15600, $registration->exam_fee_total);
        $this->assertSame(17800, $registration->service_fee_total);
        $this->assertSame(33400, $registration->total_fee);
        $this->assertSame(16700, (int) $registration->exams->first()->pivot->total_amount_snapshot);
    }

    public function test_unavailable_practice_exam_option_is_rejected_when_master_data_exists(): void
    {
        Mail::fake();
        $this->seed(ApExamSubjectSeeder::class);
        $subject = ApExamSubject::query()->firstOrFail();
        PracticeExamOption::query()->create([
            'name' => 'Active Practice Exam',
            'fee' => 2500,
            'currency' => 'NTD',
            'is_active' => true,
        ]);

        $this->post('/student-registration', $this->validPayload([
            'exam_subject_ids' => [$subject->id],
            'practice_exams' => ['Fake practice option'],
        ]))->assertSessionHasErrors(['practice_exams']);
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
            'passport_file' => null,
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
            'student_signature_name' => 'Alex Student',
            'student_signature_date' => now()->toDateString(),
            'guardian_signature_name' => 'Pat Parent',
            'guardian_signature_date' => now()->toDateString(),
        ], $overrides);
    }
}
