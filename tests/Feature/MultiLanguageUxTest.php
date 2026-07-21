<?php

namespace Tests\Feature;

use App\Mail\PassportReuploadRequested;
use App\Mail\PaymentConfirmationMail;
use App\Mail\PaymentInstructionMail;
use App\Mail\StudentRegistrationConfirmation;
use App\Models\ApExamSubject;
use App\Models\PaymentSetting;
use App\Models\RegistrationPayment;
use App\Models\StudentRegistration;
use Database\Seeders\PaymentSettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class MultiLanguageUxTest extends TestCase
{
    use RefreshDatabase;

    public function test_language_switcher_persists_and_invalid_locale_is_rejected(): void
    {
        $this->seed(PaymentSettingSeeder::class);

        $this->get('/student-registration')
            ->assertOk()
            ->assertSee('Student AP Exam Registration')
            ->assertSee('data-language-switcher', false);

        $this->get(route('locale.switch', ['locale' => 'zh-TW', 'redirect' => '/student-registration']))
            ->assertRedirect('/student-registration')
            ->assertCookie('locale', 'zh-TW');

        $this->withSession(['locale' => 'zh-TW'])
            ->get('/student-registration')
            ->assertOk()
            ->assertSee('學生 AP 考試報名');

        $this->get('/locale/fr')->assertNotFound();
    }

    public function test_missing_translation_falls_back_to_english(): void
    {
        app()->setLocale('zh_TW');

        $this->assertSame('Fallback English text', __('ap_registration.fallback_probe'));
    }

    public function test_registration_form_has_mobile_progress_errors_and_requires_confirmation(): void
    {
        $subject = $this->subject();

        $this->get('/student-registration')
            ->assertOk()
            ->assertSee('name="viewport"', false)
            ->assertSee('Registration progress')
            ->assertSee('data-step="1"', false)
            ->assertSee('confirmed_review');

        $payload = $this->payload(['exam_subject_ids' => [$subject->id]]);
        unset($payload['confirmed_review']);

        $this->post('/student-registration', $payload)
            ->assertSessionHasErrors(['confirmed_review'])
            ->assertSessionHasInput('student_full_name', 'Alex Chen');
    }

    public function test_registration_and_payment_instruction_emails_are_bilingual(): void
    {
        Mail::fake();
        $this->seed(PaymentSettingSeeder::class);
        $subject = $this->subject();

        $response = $this->withSession(['locale' => 'zh-TW'])
            ->post('/student-registration', $this->payload(['exam_subject_ids' => [$subject->id]]))
            ->assertRedirect();
        $response->assertSessionHasNoErrors();

        Mail::assertSent(StudentRegistrationConfirmation::class);
        Mail::assertSent(PaymentInstructionMail::class);

        app()->setLocale('zh_TW');
        $registration = StudentRegistration::query()->with(['contact', 'exams'])->firstOrFail();
        $payment = RegistrationPayment::query()->with('registration.contact')->firstOrFail();
        $setting = PaymentSetting::query()->firstOrFail();
        $this->assertTrue((new StudentRegistrationConfirmation($registration))->build()->hasSubject('已收到 AP 考試報名 - '.$registration->registration_number));
        $this->assertTrue((new PaymentInstructionMail($payment, $setting))->build()->hasSubject('AP 付款說明 '.$registration->registration_number));
    }

    public function test_payment_confirmation_email_chinese_and_missing_document_email_has_no_sensitive_file_path(): void
    {
        app()->setLocale('zh_TW');
        [$registration, $payment] = $this->registrationAndPayment();

        $paymentMail = new PaymentConfirmationMail($payment->load(['registration.contact', 'registration.exams']));
        $this->assertTrue($paymentMail->build()->hasSubject('AP 付款狀態更新 '.$registration->registration_number));

        $registration->forceFill([
            'passport_reupload_reason' => 'Image is blurry.',
            'passport_reupload_deadline_at' => now()->addWeek(),
            'passport_file_path' => 'student-passports/private-passport.pdf',
        ]);
        $passportMail = new PassportReuploadRequested($registration->load('contact'));
        $html = $passportMail->render();

        $this->assertStringContainsString('Image is blurry.', $html);
        $this->assertStringNotContainsString('student-passports/private-passport.pdf', $html);
    }

    private function subject(): ApExamSubject
    {
        return ApExamSubject::query()->create([
            'name' => 'Calculus AB',
            'code' => 'CALAB'.Str::random(5),
            'category' => 'Mathematics',
            'exam_date' => '2027-05-04',
            'exam_fee' => 7800,
            'service_fee' => 1200,
            'late_registration_fee' => 0,
            'currency' => 'NTD',
            'status' => 'open',
            'registration_open_at' => now()->subMonth(),
            'registration_close_at' => now()->addMonth(),
            'is_active' => true,
        ]);
    }

    private function registrationAndPayment(): array
    {
        $registration = StudentRegistration::query()->create([
            'registration_number' => 'APR-2026-'.str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT),
            'status' => 'submitted',
            'payment_status' => 'paid',
            'student_full_name' => 'Alex Chen',
            'date_of_birth' => '2009-01-15',
            'nationality' => 'Taiwan',
            'passport_number' => 'A12345678',
            'student_email' => 'alex@example.com',
            'school_name' => 'Taipei International School',
            'school_country' => 'Taiwan',
            'grade_level' => '11',
            'exam_fee_total' => 7800,
            'service_fee_total' => 1200,
            'late_fee_total' => 0,
            'total_fee' => 9000,
            'grand_total' => 9000,
            'currency' => 'NTD',
            'fee_snapshot_at' => now(),
            'submitted_at' => now(),
        ]);
        $registration->contact()->create([
            'parent_full_name' => 'Ivon Jou',
            'relationship' => 'Mother',
            'parent_email' => 'parent@example.com',
            'parent_phone' => '+886 987 654 321',
            'emergency_contact_name' => 'Mark Jou',
            'emergency_contact_phone' => '+886 988 111 222',
            'emergency_contact_relationship' => 'Father',
        ]);
        $payment = RegistrationPayment::query()->create([
            'student_registration_id' => $registration->id,
            'payment_reference' => $registration->registration_number.'-PAY',
            'provider' => 'manual',
            'payment_method' => 'manual_bank_transfer',
            'payment_status' => 'paid',
            'exam_fee_amount' => 7800,
            'service_fee_amount' => 1200,
            'late_fee_amount' => 0,
            'grand_total' => 9000,
            'currency' => 'NTD',
            'transaction_id' => 'TX123',
            'paid_at' => now(),
        ]);

        return [$registration->fresh(['contact', 'exams']), $payment->fresh(['registration.contact', 'registration.exams'])];
    }

    private function payload(array $overrides = []): array
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
            'student_signature_name' => 'Alex Student',
            'student_signature_date' => now()->toDateString(),
            'guardian_signature_name' => 'Pat Parent',
            'guardian_signature_date' => now()->toDateString(),
        ], $overrides);
    }
}
