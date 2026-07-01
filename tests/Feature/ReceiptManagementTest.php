<?php

namespace Tests\Feature;

use App\Mail\ReceiptIssuedMail;
use App\Mail\ReceiptRequestReceivedMail;
use App\Models\EInvoiceSetting;
use App\Models\ReceiptRequest;
use App\Models\RegistrationPayment;
use App\Models\StudentRegistration;
use App\Models\User;
use App\Services\ReceiptService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class ReceiptManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_receipt_amount_uses_service_fee_only_by_default(): void
    {
        Mail::fake();
        [$registration, $payment] = $this->registrationAndPayment();

        $this->get(route('receipts.create', $payment))
            ->assertOk()
            ->assertSee('Service Fee Receipt Amount')
            ->assertSee('1,200')
            ->assertSee('9,300');

        $this->post(route('receipts.store', $payment), [
            'receipt_type' => 'personal',
            'buyer_name' => 'Ivon Jou',
            'buyer_email' => 'ivon@example.com',
            'buyer_phone' => '+886 987 654 321',
        ])->assertRedirect();

        $receipt = ReceiptRequest::query()->firstOrFail();
        $this->assertSame(7800, $receipt->exam_fee_amount);
        $this->assertSame(1200, $receipt->taxable_receipt_amount);
        $this->assertSame(9300, $receipt->non_receipt_amount);
        $this->assertSame('pending_issue', $receipt->status);
        Mail::assertSent(ReceiptRequestReceivedMail::class);
        $this->assertDatabaseHas('receipt_logs', [
            'receipt_request_id' => $receipt->id,
            'event_type' => 'receipt_request_saved',
        ]);
    }

    public function test_company_receipt_requires_valid_gui_and_personal_does_not(): void
    {
        [$registration, $payment] = $this->registrationAndPayment();

        $this->post(route('receipts.store', $payment), [
            'receipt_type' => 'company',
            'buyer_name' => 'Ivon Jou',
            'buyer_email' => 'ivon@example.com',
            'buyer_phone' => '+886 987 654 321',
        ])->assertSessionHasErrors(['company_name', 'gui_tax_id']);

        $this->post(route('receipts.store', $payment), [
            'receipt_type' => 'company',
            'buyer_name' => 'Ivon Jou',
            'buyer_email' => 'ivon@example.com',
            'buyer_phone' => '+886 987 654 321',
            'company_name' => 'Trinity Scholar',
            'gui_tax_id' => '12345678',
        ])->assertSessionHasErrors(['gui_tax_id']);

        $this->post(route('receipts.store', $payment), [
            'receipt_type' => 'personal',
            'buyer_name' => 'Ivon Jou',
            'buyer_email' => 'ivon@example.com',
            'buyer_phone' => '+886 987 654 321',
        ])->assertRedirect();

        $this->assertSame('personal', ReceiptRequest::query()->firstOrFail()->receipt_type);
        $this->assertNotNull($registration);
    }

    public function test_late_fee_can_be_configured_as_taxable(): void
    {
        EInvoiceSetting::query()->create([
            'provider' => 'manual',
            'environment' => 'sandbox',
            'late_fee_taxable' => true,
            'is_active' => true,
        ]);
        [$registration, $payment] = $this->registrationAndPayment();

        $receipt = app(ReceiptService::class)->saveRequest($payment, [
            'receipt_type' => 'personal',
            'buyer_name' => 'Ivon Jou',
            'buyer_email' => 'ivon@example.com',
            'buyer_phone' => '+886 987 654 321',
        ], '127.0.0.1');

        $this->assertSame(2700, $receipt->taxable_receipt_amount);
        $this->assertSame(7800, $receipt->non_receipt_amount);
        $this->assertNotNull($registration);
    }

    public function test_admin_can_list_filter_export_and_issue_receipt(): void
    {
        Mail::fake();
        [$registration, $payment] = $this->registrationAndPayment();
        $receipt = app(ReceiptService::class)->saveRequest($payment, [
            'receipt_type' => 'personal',
            'buyer_name' => 'Ivon Jou',
            'buyer_email' => 'ivon@example.com',
            'buyer_phone' => '+886 987 654 321',
        ], '127.0.0.1');
        $admin = $this->adminUser();

        $this->actingAs(User::query()->create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => Hash::make('StrongPass!123'),
            'is_admin' => false,
        ]))->get(route('admin.receipts.index'))->assertForbidden();

        $this->actingAs($admin)
            ->get(route('admin.receipts.index', ['status' => 'pending_issue']))
            ->assertOk()
            ->assertSee($registration->registration_number);

        $this->actingAs($admin)
            ->get(route('admin.receipts.export'))
            ->assertOk()
            ->assertHeader('Content-Type', 'text/csv; charset=utf-8');

        $this->actingAs($admin)
            ->post(route('admin.receipts.issue', $receipt), [
                'receipt_number' => 'FA-2026-0001',
                'notes' => 'Issued manually.',
            ])->assertRedirect(route('admin.receipts.show', $receipt));

        $receipt->refresh();
        $this->assertSame('issued', $receipt->status);
        $this->assertSame('FA-2026-0001', $receipt->receipt_number);
        Mail::assertSent(ReceiptIssuedMail::class);
        $this->assertDatabaseHas('receipt_logs', [
            'receipt_request_id' => $receipt->id,
            'event_type' => 'receipt_issued',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.receipts.issue', $receipt), [
                'receipt_number' => 'FA-2026-0002',
            ])->assertSessionHasErrors(['receipt_number']);
    }

    public function test_receipt_issue_requires_paid_payment_unless_setting_allows(): void
    {
        [$registration, $payment] = $this->registrationAndPayment(['payment_status' => 'pending']);
        $receipt = app(ReceiptService::class)->saveRequest($payment, [
            'receipt_type' => 'personal',
            'buyer_name' => 'Ivon Jou',
            'buyer_email' => 'ivon@example.com',
            'buyer_phone' => '+886 987 654 321',
        ], '127.0.0.1');

        $this->actingAs($this->adminUser())
            ->post(route('admin.receipts.issue', $receipt), [
                'receipt_number' => 'FA-2026-0001',
            ])->assertSessionHasErrors(['receipt_number']);

        $this->assertSame('pending_issue', $receipt->fresh()->status);
        $this->assertNotNull($registration);
    }

    public function test_auto_fapiao_failure_is_logged_and_settings_encrypt_credentials(): void
    {
        [$registration, $payment] = $this->registrationAndPayment();
        $receipt = ReceiptRequest::query()->create([
            'student_registration_id' => $registration->id,
            'registration_payment_id' => $payment->id,
            'receipt_type' => 'personal',
            'buyer_name' => 'Ivon Jou',
            'buyer_email' => null,
            'buyer_phone' => '+886 987 654 321',
            'exam_fee_amount' => 7800,
            'service_fee_amount' => 1200,
            'late_fee_amount' => 1500,
            'taxable_receipt_amount' => 1200,
            'non_receipt_amount' => 9300,
            'currency' => 'NTD',
            'status' => 'pending_issue',
        ]);
        $admin = $this->adminUser();

        $this->actingAs($admin)
            ->put(route('admin.receipts.settings.update'), [
                'provider' => 'ecpay',
                'environment' => 'sandbox',
                'merchant_id' => 'MID',
                'api_key' => 'api-secret',
                'hash_key' => 'hash-secret',
                'hash_iv' => 'iv-secret',
                'late_fee_taxable' => '1',
                'is_active' => '1',
            ])->assertRedirect(route('admin.receipts.settings'));

        $setting = EInvoiceSetting::query()->firstOrFail();
        $this->assertNotSame('api-secret', $setting->api_key_encrypted);

        $this->actingAs($admin)
            ->post(route('admin.receipts.auto-issue', $receipt))
            ->assertRedirect(route('admin.receipts.show', $receipt));

        $this->assertSame('failed', $receipt->fresh()->status);
        $this->assertDatabaseHas('e_invoice_transactions', [
            'receipt_request_id' => $receipt->id,
            'provider_status' => 'failed',
        ]);
        $this->assertDatabaseHas('receipt_logs', [
            'receipt_request_id' => $receipt->id,
            'event_type' => 'e_invoice_failed',
        ]);
    }

    private function registrationAndPayment(array $paymentOverrides = []): array
    {
        $registration = StudentRegistration::query()->create([
            'registration_number' => 'APR-2026-'.str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT),
            'status' => 'submitted',
            'registration_period' => 'late',
            'payment_status' => $paymentOverrides['payment_status'] ?? 'paid',
            'student_full_name' => 'Alex Chen',
            'date_of_birth' => '2009-01-15',
            'nationality' => 'Taiwan',
            'passport_number' => 'A'.random_int(10000000, 99999999),
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
            'payment_status' => 'paid',
            'exam_fee_amount' => 7800,
            'service_fee_amount' => 1200,
            'late_fee_amount' => 1500,
            'grand_total' => 10500,
            'currency' => 'NTD',
            'paid_at' => now(),
        ], $paymentOverrides));

        return [$registration->fresh(['contact']), $payment->fresh(['registration'])];
    }

    private function adminUser(): User
    {
        return User::query()->create([
            'name' => 'Admin',
            'email' => Str::lower(Str::random(8)).'@example.com',
            'password' => Hash::make('StrongPass!123'),
            'is_admin' => true,
        ]);
    }
}
