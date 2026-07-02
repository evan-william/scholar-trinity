<?php

namespace App\Services;

use App\Mail\ReceiptIssuedMail;
use App\Mail\ReceiptRequestReceivedMail;
use App\Models\EInvoiceSetting;
use App\Models\EInvoiceTransaction;
use App\Models\ReceiptLog;
use App\Models\ReceiptRequest;
use App\Models\RegistrationPayment;
use App\Services\EInvoices\EInvoiceProviderManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class ReceiptService
{
    public function activeSetting(): EInvoiceSetting
    {
        return EInvoiceSetting::query()->where('is_active', true)->latest()->first()
            ?? EInvoiceSetting::query()->create([
                'provider' => 'manual',
                'environment' => 'sandbox',
                'late_fee_taxable' => false,
                'allow_unpaid_receipts' => false,
                'is_active' => true,
            ]);
    }

    public function taxableAmount(RegistrationPayment $payment): int
    {
        $setting = $this->activeSetting();

        return $payment->service_fee_amount + ($setting->late_fee_taxable ? $payment->late_fee_amount : 0);
    }

    public function nonReceiptAmount(RegistrationPayment $payment): int
    {
        return max($payment->grand_total - $this->taxableAmount($payment), 0);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function saveRequest(RegistrationPayment $payment, array $data, ?string $ipAddress): ReceiptRequest
    {
        $payment->load('registration.contact');
        $type = $data['receipt_type'];
        $status = $type === 'none' ? 'not_requested' : 'pending_issue';

        return DB::transaction(function () use ($payment, $data, $type, $status, $ipAddress): ReceiptRequest {
            $receipt = ReceiptRequest::query()->updateOrCreate(
                ['registration_payment_id' => $payment->id],
                [
                    'student_registration_id' => $payment->student_registration_id,
                    'buyer_name' => $type === 'none' ? null : $data['buyer_name'],
                    'buyer_email' => $type === 'none' ? null : $data['buyer_email'],
                    'buyer_phone' => $type === 'none' ? null : $data['buyer_phone'],
                    'company_name' => $data['company_name'] ?? null,
                    'gui_tax_id' => $data['gui_tax_id'] ?? null,
                    'receipt_type' => $type,
                    'exam_fee_amount' => $payment->exam_fee_amount,
                    'service_fee_amount' => $payment->service_fee_amount,
                    'late_fee_amount' => $payment->late_fee_amount,
                    'taxable_receipt_amount' => $type === 'none' ? 0 : $this->taxableAmount($payment),
                    'non_receipt_amount' => $type === 'none' ? $payment->grand_total : $this->nonReceiptAmount($payment),
                    'currency' => $payment->currency,
                    'status' => $status,
                ]
            );

            $this->log($receipt, 'receipt_request_saved', null, $status, null, $ipAddress, [
                'receipt_type' => $type,
                'taxable_receipt_amount' => $receipt->taxable_receipt_amount,
            ]);
            app(SecurityAuditService::class)->log('receipt', 'receipt_requested', 'Receipt request saved.', $receipt, [], ['status' => $status], ['receipt_type' => $type]);

            if ($type !== 'none') {
                Mail::to($receipt->buyer_email)->send(new ReceiptRequestReceivedMail($receipt->load('registration')));
            }

            return $receipt->fresh(['registration', 'payment']);
        });
    }

    /**
     * @param array<string, mixed> $data
     */
    public function updateByAdmin(ReceiptRequest $receipt, array $data, int $adminId, ?string $ipAddress): ReceiptRequest
    {
        $old = $receipt->status;
        $receipt->update([
            'buyer_name' => $data['buyer_name'],
            'buyer_email' => $data['buyer_email'],
            'buyer_phone' => $data['buyer_phone'],
            'company_name' => $data['company_name'] ?? null,
            'gui_tax_id' => $data['gui_tax_id'] ?? null,
            'receipt_type' => $data['receipt_type'],
            'notes' => $data['notes'] ?? $receipt->notes,
        ]);
        $this->log($receipt, 'receipt_information_updated', $old, $receipt->status, $adminId, $ipAddress);
        app(SecurityAuditService::class)->log('receipt', 'receipt_information_updated', 'Receipt information updated.', $receipt, [], [], [], 'success', request(), $adminId);

        return $receipt->fresh();
    }

    public function markIssued(ReceiptRequest $receipt, string $receiptNumber, int $adminId, ?string $ipAddress, ?string $notes = null): ReceiptRequest
    {
        if (in_array($receipt->status, ['issued', 'sent'], true)) {
            throw ValidationException::withMessages(['receipt_number' => __('receipt.validation.already_issued')]);
        }

        if (! $this->activeSetting()->allow_unpaid_receipts && $receipt->payment?->payment_status !== 'paid') {
            throw ValidationException::withMessages(['receipt_number' => __('receipt.validation.payment_required')]);
        }

        $old = $receipt->status;
        $receipt->update([
            'status' => 'issued',
            'receipt_number' => $receiptNumber,
            'issued_at' => now(),
            'issued_by' => $adminId,
            'notes' => $notes ?: $receipt->notes,
        ]);

        $this->log($receipt, 'receipt_issued', $old, 'issued', $adminId, $ipAddress, [
            'receipt_number' => $receiptNumber,
        ]);
        app(SecurityAuditService::class)->log('receipt', 'receipt_issued', 'Receipt marked issued.', $receipt, ['status' => $old], ['status' => 'issued', 'receipt_number' => $receiptNumber], [], 'success', request(), $adminId);
        Mail::to($receipt->buyer_email)->send(new ReceiptIssuedMail($receipt->fresh(['registration', 'payment'])));

        return $receipt->fresh();
    }

    public function updateStatus(ReceiptRequest $receipt, string $status, int $adminId, ?string $ipAddress, ?string $notes = null): ReceiptRequest
    {
        $old = $receipt->status;
        $receipt->update([
            'status' => $status,
            'sent_at' => $status === 'sent' ? now() : $receipt->sent_at,
            'notes' => $notes ?: $receipt->notes,
        ]);
        $this->log($receipt, 'receipt_status_updated', $old, $status, $adminId, $ipAddress);
        app(SecurityAuditService::class)->log('receipt', 'receipt_status_changed', 'Receipt status changed.', $receipt, ['status' => $old], ['status' => $status], [], 'success', request(), $adminId);

        return $receipt->fresh();
    }

    public function sendEmail(ReceiptRequest $receipt, int $adminId, ?string $ipAddress): void
    {
        Mail::to($receipt->buyer_email)->send(new ReceiptIssuedMail($receipt->load('registration', 'payment')));
        $old = $receipt->status;
        $receipt->update(['status' => 'sent', 'sent_at' => now()]);
        $this->log($receipt, 'receipt_email_sent', $old, 'sent', $adminId, $ipAddress);
    }

    public function simulateAutoIssue(ReceiptRequest $receipt): EInvoiceTransaction
    {
        $setting = $this->activeSetting();
        $transaction = app(EInvoiceProviderManager::class)->forSetting($setting)->issue($receipt, $setting);

        if ($transaction->provider_status === 'failed') {
            $old = $receipt->status;
            $receipt->update(['status' => 'failed']);
            $this->log($receipt, 'e_invoice_failed', $old, 'failed', null, request()?->ip(), ['error' => $transaction->error_message]);
            Log::warning('E-invoice sandbox issue failed.', ['receipt' => $receipt->uuid]);
        }

        return $transaction;
    }

    public function log(ReceiptRequest $receipt, string $event, ?string $old, ?string $new, ?int $adminId, ?string $ipAddress, array $payload = []): void
    {
        ReceiptLog::query()->create([
            'receipt_request_id' => $receipt->id,
            'student_registration_id' => $receipt->student_registration_id,
            'event_type' => $event,
            'old_status' => $old,
            'new_status' => $new,
            'payload' => $payload,
            'performed_by' => $adminId,
            'performed_ip' => $ipAddress,
        ]);
    }
}
