<?php

namespace Tests\Feature;

use App\Mail\PaymentConfirmationMail;
use App\Models\ApExamSubject;
use App\Models\PaymentSetting;
use App\Models\RegistrationPayment;
use App\Models\StudentRegistration;
use App\Models\User;
use App\Services\PaymentFlowService;
use Database\Seeders\PaymentSettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class PaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_creates_fee_snapshot_and_payment_record(): void
    {
        Mail::fake();
        $this->seed(PaymentSettingSeeder::class);
        $subject = $this->subject();

        $this->post('/student-registration', $this->payload(['exam_subject_ids' => [$subject->id]]))
            ->assertRedirect();

        $registration = StudentRegistration::query()->firstOrFail();
        $payment = RegistrationPayment::query()->firstOrFail();

        $this->assertSame(7800, $registration->exam_fee_total);
        $this->assertSame(1200, $registration->service_fee_total);
        $this->assertSame(1500, $registration->late_fee_total);
        $this->assertSame(10500, $registration->grand_total);
        $this->assertSame('NTD', $registration->currency);
        $this->assertNotNull($registration->fee_snapshot_at);

        $subject->update(['exam_fee' => 9999, 'service_fee' => 2222, 'late_registration_fee' => 3333]);
        $this->assertSame(10500, $registration->fresh()->grand_total);
        $this->assertSame(10500, $payment->grand_total);
    }

    public function test_manual_payment_instruction_and_proof_upload(): void
    {
        Storage::fake('local');
        [$registration, $payment] = $this->registrationAndPayment();

        $this->get(route('payments.show', $registration->registration_number))
            ->assertOk()
            ->assertSee('Bank Transfer')
            ->assertSee($payment->payment_reference);

        $this->post(route('payments.proof.upload', $payment), [
            'proof' => UploadedFile::fake()->create('proof.exe', 10),
        ])->assertSessionHasErrors(['proof']);

        $this->post(route('payments.proof.upload', $payment), [
            'proof' => UploadedFile::fake()->image('proof.jpg'),
        ])->assertRedirect(route('payments.show', $registration->registration_number));

        $payment->refresh();
        $this->assertSame('waiting_verification', $payment->payment_status);
        $this->assertSame('waiting_verification', $registration->fresh()->payment_status);
        Storage::disk('local')->assertExists($payment->proof_file_path);
        $this->assertDatabaseHas('payment_logs', [
            'registration_payment_id' => $payment->id,
            'event_type' => 'manual_proof_uploaded',
        ]);
    }

    public function test_admin_can_verify_and_reject_manual_payment(): void
    {
        Mail::fake();
        [$registration, $payment] = $this->registrationAndPayment(['payment_status' => 'waiting_verification']);
        $admin = $this->adminUser();

        $this->actingAs(User::query()->create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => Hash::make('StrongPass!123'),
            'is_admin' => false,
        ]))->get(route('admin.payments.index'))->assertForbidden();

        $this->actingAs($admin)
            ->post(route('admin.payments.verify', $payment), [
                'action' => 'verify',
                'note' => 'Bank transfer matched.',
            ])->assertRedirect(route('admin.payments.show', $payment));

        $this->assertSame('paid', $payment->fresh()->payment_status);
        $this->assertSame('paid', $registration->fresh()->payment_status);
        Mail::assertSent(PaymentConfirmationMail::class);

        [$registration2, $payment2] = $this->registrationAndPayment(['payment_status' => 'waiting_verification'], 'APR-2026-999002');
        $this->actingAs($admin)
            ->post(route('admin.payments.verify', $payment2), [
                'action' => 'reject',
            ])->assertSessionHasErrors(['rejected_reason']);

        $this->actingAs($admin)
            ->post(route('admin.payments.verify', $payment2), [
                'action' => 'reject',
                'rejected_reason' => 'Amount does not match.',
            ])->assertRedirect(route('admin.payments.show', $payment2));

        $this->assertSame('rejected', $payment2->fresh()->payment_status);
        $this->assertSame('failed', $registration2->fresh()->payment_status);
    }

    public function test_gateway_callback_signature_amount_and_duplicate_processing(): void
    {
        Mail::fake();
        [$registration, $payment] = $this->registrationAndPayment();
        $setting = PaymentSetting::query()->create([
            'provider' => 'ecpay',
            'mode' => 'sandbox',
            'merchant_id' => 'MID',
            'is_active' => true,
        ]);
        $setting->setHashKey('sandbox-key');
        $setting->setHashIv('sandbox-iv');
        $setting->save();

        $service = app(PaymentFlowService::class);
        $service->gatewayPayload($payment->load('registration'));
        $payment->refresh();

        $payload = [
            'MerchantTradeNo' => $payment->gateway_order_id,
            'TradeAmt' => $payment->grand_total,
            'RtnCode' => '1',
            'TradeNo' => 'TX12345',
        ];
        $payload['CheckMacValue'] = $service->signature($payload, $setting);

        $this->post(route('payments.gateway.callback'), $payload)->assertOk()->assertSee('1|OK');
        $this->assertSame('paid', $payment->fresh()->payment_status);
        $this->assertSame('paid', $registration->fresh()->payment_status);
        $this->assertSame('TX12345', $payment->fresh()->transaction_id);

        $this->post(route('payments.gateway.callback'), $payload)->assertOk();
        $this->assertSame(1, $payment->logs()->where('event_type', 'gateway_payment_paid')->count());

        [$badRegistration, $badPayment] = $this->registrationAndPayment([], 'APR-2026-999003');
        $service->gatewayPayload($badPayment->load('registration'));
        $badPayment->refresh();
        $badPayload = [
            'MerchantTradeNo' => $badPayment->gateway_order_id,
            'TradeAmt' => $badPayment->grand_total + 1,
            'RtnCode' => '1',
            'TradeNo' => 'BADTX',
        ];
        $badPayload['CheckMacValue'] = $service->signature($badPayload, $setting);
        $this->post(route('payments.gateway.callback'), $badPayload)->assertStatus(422);
        $this->assertSame('pending', $badRegistration->fresh()->payment_status);

        $invalidSignature = $payload;
        $invalidSignature['CheckMacValue'] = 'INVALID';
        $this->post(route('payments.gateway.callback'), $invalidSignature)->assertForbidden();
    }

    public function test_payment_settings_store_encrypted_credentials(): void
    {
        $admin = $this->adminUser();

        $this->actingAs($admin)
            ->put(route('admin.payments.settings.update'), [
                'provider' => 'ecpay',
                'mode' => 'sandbox',
                'merchant_id' => 'MERCHANT',
                'hash_key' => 'secret-key',
                'hash_iv' => 'secret-iv',
                'bank_name' => 'Taiwan Bank',
                'payment_deadline_days' => 5,
                'is_active' => '1',
            ])->assertRedirect(route('admin.payments.settings'));

        $setting = PaymentSetting::query()->firstOrFail();
        $this->assertNotSame('secret-key', $setting->hash_key_encrypted);
        $this->assertSame('secret-key', $setting->hashKey());
    }

    private function registrationAndPayment(array $paymentOverrides = [], string $number = 'APR-2026-999001'): array
    {
        $registration = StudentRegistration::query()->create([
            'registration_number' => $number,
            'status' => 'submitted',
            'registration_period' => 'late',
            'payment_status' => 'pending',
            'student_full_name' => 'Alex Chen',
            'date_of_birth' => '2009-01-15',
            'nationality' => 'Taiwan',
            'passport_number' => 'A12345678'.substr($number, -1),
            'student_email' => Str::lower(Str::random(6)).'@example.com',
            'school_name' => 'Taipei International School',
            'school_country' => 'Taiwan',
            'grade_level' => '11',
            'exam_fee_total' => 7800,
            'service_fee_total' => 1200,
            'late_fee_total' => 1500,
            'total_fee' => 10500,
            'grand_total' => 10500,
            'currency' => 'NTD',
            'fee_snapshot_at' => now(),
            'submitted_at' => now(),
        ]);
        $registration->contact()->create([
            'parent_full_name' => 'Ivon Jou',
            'relationship' => 'Mother',
            'parent_email' => Str::lower(Str::random(6)).'@example.com',
            'parent_phone' => '+886 987 654 321',
            'emergency_contact_name' => 'Mark Jou',
            'emergency_contact_phone' => '+886 988 111 222',
            'emergency_contact_relationship' => 'Father',
        ]);

        $payment = RegistrationPayment::query()->create(array_replace([
            'student_registration_id' => $registration->id,
            'payment_reference' => $registration->registration_number.'-PAY',
            'provider' => 'manual',
            'payment_method' => 'manual_bank_transfer',
            'payment_status' => 'pending',
            'exam_fee_amount' => 7800,
            'service_fee_amount' => 1200,
            'late_fee_amount' => 1500,
            'grand_total' => 10500,
            'currency' => 'NTD',
            'payment_deadline_at' => now()->addWeek(),
        ], $paymentOverrides));

        return [$registration->fresh(['contact']), $payment->fresh('registration')];
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
            'late_registration_fee' => 1500,
            'currency' => 'NTD',
            'status' => 'open',
            'registration_open_at' => now()->subMonth(),
            'registration_close_at' => now()->addMonth(),
            'late_registration_start_at' => now()->subDay(),
            'late_registration_end_at' => now()->addMonth(),
            'is_active' => true,
        ]);
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
        ], $overrides);
    }

    private function adminUser(): User
    {
        return User::query()->create([
            'name' => 'Admin',
            'email' => Str::lower(Str::random(6)).'@example.com',
            'password' => Hash::make('StrongPass!123'),
            'is_admin' => true,
        ]);
    }
}
