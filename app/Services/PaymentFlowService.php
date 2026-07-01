<?php

namespace App\Services;

use App\Mail\PaymentConfirmationMail;
use App\Mail\PaymentInstructionMail;
use App\Models\PaymentLog;
use App\Models\PaymentSetting;
use App\Models\RegistrationPayment;
use App\Models\StudentRegistration;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentFlowService
{
    public function activeSetting(): PaymentSetting
    {
        return PaymentSetting::query()->where('is_active', true)->latest()->first()
            ?? PaymentSetting::query()->create([
                'provider' => 'manual',
                'mode' => 'sandbox',
                'bank_name' => 'Taiwan Bank',
                'bank_code' => '004',
                'account_name' => 'Trinity Scholar',
                'account_number' => '000-000-000000',
                'manual_instruction' => 'Please include your registration reference in the transfer note.',
                'is_active' => true,
            ]);
    }

    public function ensurePayment(StudentRegistration $registration, string $method = 'manual_bank_transfer'): RegistrationPayment
    {
        $existing = $registration->payments()->whereIn('payment_status', ['pending', 'proof_uploaded', 'waiting_verification', 'paid'])->latest()->first();
        if ($existing) {
            return $existing;
        }

        $setting = $this->activeSetting();

        return DB::transaction(function () use ($registration, $method, $setting): RegistrationPayment {
            $payment = RegistrationPayment::query()->create([
                'student_registration_id' => $registration->id,
                'payment_reference' => $registration->registration_number.'-PAY',
                'provider' => $method === 'manual_bank_transfer' ? 'manual' : $setting->provider,
                'payment_method' => $method,
                'payment_status' => 'pending',
                'exam_fee_amount' => $registration->exam_fee_total,
                'service_fee_amount' => $registration->service_fee_total,
                'late_fee_amount' => $registration->late_fee_total,
                'grand_total' => $registration->grand_total ?: $registration->total_fee,
                'currency' => $registration->currency ?: 'NTD',
                'payment_deadline_at' => now()->addDays($setting->payment_deadline_days ?: 7),
            ]);

            $this->log($payment, 'payment_created', null, 'pending', null, request()?->ip(), [
                'method' => $method,
            ]);

            Mail::to($registration->student_email)
                ->cc($registration->contact?->parent_email)
                ->send(new PaymentInstructionMail($payment->load('registration.contact'), $setting));

            return $payment;
        });
    }

    public function uploadProof(RegistrationPayment $payment, UploadedFile $file, ?string $ipAddress): RegistrationPayment
    {
        app(FileSecurityService::class)->validate($file, 'proof');
        abort_if(in_array($payment->payment_status, ['paid', 'refunded', 'cancelled'], true), 422);

        $old = $payment->payment_status;
        $path = $file->store('payment-proofs', 'local');
        $payment->update([
            'proof_file_path' => $path,
            'proof_original_name' => basename($file->getClientOriginalName()),
            'proof_mime_type' => $file->getMimeType(),
            'proof_file_size' => $file->getSize(),
            'proof_uploaded_at' => now(),
            'payment_status' => 'waiting_verification',
        ]);
        $payment->registration()->update([
            'payment_status' => 'waiting_verification',
            'payment_method' => 'manual_bank_transfer',
            'payment_reference' => $payment->payment_reference,
            'payment_amount' => $payment->grand_total,
        ]);
        $this->log($payment, 'manual_proof_uploaded', $old, 'waiting_verification', null, $ipAddress);
        app(SecurityAuditService::class)->log('payment', 'manual_proof_uploaded', 'Manual proof uploaded.', $payment, ['payment_status' => $old], ['payment_status' => 'waiting_verification']);

        return $payment->fresh(['registration.contact', 'registration.exams']);
    }

    public function verifyManual(RegistrationPayment $payment, int $adminId, ?string $note, ?string $ipAddress): RegistrationPayment
    {
        return $this->setPaid($payment, [
            'provider' => 'manual',
            'transaction_id' => 'MANUAL-'.$payment->payment_reference,
            'gateway_payload' => ['note' => $note],
        ], $adminId, $ipAddress, 'manual_payment_verified');
    }

    public function rejectManual(RegistrationPayment $payment, int $adminId, string $reason, ?string $ipAddress): RegistrationPayment
    {
        $old = $payment->payment_status;
        $payment->update([
            'payment_status' => 'rejected',
            'rejected_reason' => $reason,
            'verified_by' => $adminId,
            'verified_at' => now(),
        ]);
        $payment->registration()->update(['payment_status' => 'failed']);
        $this->log($payment, 'manual_payment_rejected', $old, 'rejected', $adminId, $ipAddress, ['reason' => $reason]);
        app(SecurityAuditService::class)->log('payment', 'manual_payment_rejected', 'Manual payment rejected.', $payment, ['payment_status' => $old], ['payment_status' => 'rejected'], ['reason' => $reason]);
        $this->sendConfirmation($payment->fresh(['registration.contact', 'registration.exams']));

        return $payment->fresh();
    }

    /**
     * @return array<string, string|int>
     */
    public function gatewayPayload(RegistrationPayment $payment): array
    {
        $setting = $this->activeSetting();
        $orderId = $payment->gateway_order_id ?: 'AP'.now()->format('YmdHis').$payment->id;
        $payment->update([
            'provider' => $setting->provider,
            'payment_method' => $payment->payment_method === 'manual_bank_transfer' ? 'credit_card' : $payment->payment_method,
            'gateway_order_id' => $orderId,
        ]);

        $payload = [
            'MerchantID' => $setting->merchant_id ?: 'SANDBOX',
            'MerchantTradeNo' => $orderId,
            'MerchantTradeDate' => now()->format('Y/m/d H:i:s'),
            'TotalAmount' => $payment->grand_total,
            'TradeDesc' => 'AP Exam Registration '.$payment->registration->registration_number,
            'ItemDesc' => 'AP Exam Fee + Service Fee',
            'ReturnURL' => $setting->callback_url ?: route('payments.gateway.callback'),
            'ClientBackURL' => $setting->return_url ?: route('payments.success', $payment->uuid),
            'ChoosePayment' => $this->gatewayPaymentMethod($payment->payment_method),
        ];
        $payload['CheckMacValue'] = $this->signature($payload, $setting);
        $this->log($payment, 'gateway_request_created', $payment->payment_status, $payment->payment_status, null, request()?->ip(), [
            'gateway_order_id' => $orderId,
        ]);

        return $payload;
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function handleGatewayCallback(array $payload, ?string $ipAddress): RegistrationPayment
    {
        $setting = $this->activeSetting();
        abort_unless($this->verifySignature($payload, $setting), 403);

        $orderId = (string) ($payload['MerchantTradeNo'] ?? $payload['gateway_order_id'] ?? '');
        $payment = RegistrationPayment::query()->with(['registration.contact', 'registration.exams'])
            ->where('gateway_order_id', $orderId)
            ->firstOrFail();

        $this->log($payment, 'gateway_callback_received', $payment->payment_status, $payment->payment_status, null, $ipAddress, $this->safePayload($payload));
        app(SecurityAuditService::class)->log('payment', 'gateway_callback_received', 'Gateway callback received.', $payment, [], [], $this->safePayload($payload), 'success', request());

        if ($payment->payment_status === 'paid') {
            return $payment;
        }

        abort_unless((int) ($payload['TradeAmt'] ?? $payload['TotalAmount'] ?? 0) === $payment->grand_total, 422);
        abort_unless((string) ($payload['RtnCode'] ?? $payload['status'] ?? '') === '1', 422);

        return $this->setPaid($payment, [
            'provider' => $setting->provider,
            'transaction_id' => (string) ($payload['TradeNo'] ?? $payload['transaction_id'] ?? ''),
            'gateway_payload' => $this->safePayload($payload),
        ], null, $ipAddress, 'gateway_payment_paid');
    }

    /**
     * @param array<string, mixed> $data
     */
    private function setPaid(RegistrationPayment $payment, array $data, ?int $adminId, ?string $ipAddress, string $event): RegistrationPayment
    {
        $old = $payment->payment_status;
        $payment->update([
            'provider' => $data['provider'],
            'payment_status' => 'paid',
            'transaction_id' => $data['transaction_id'],
            'gateway_payload' => $data['gateway_payload'],
            'paid_at' => now(),
            'verified_by' => $adminId,
            'verified_at' => now(),
            'rejected_reason' => null,
        ]);
        $payment->registration()->update([
            'payment_status' => 'paid',
            'payment_method' => $payment->payment_method,
            'payment_reference' => $payment->payment_reference,
            'payment_date' => now(),
            'payment_amount' => $payment->grand_total,
        ]);
        $this->log($payment, $event, $old, 'paid', $adminId, $ipAddress);
        app(SecurityAuditService::class)->log('payment', $event, 'Payment status changed.', $payment, ['payment_status' => $old], ['payment_status' => 'paid'], [], 'success', request(), $adminId);
        $this->sendConfirmation($payment->fresh(['registration.contact', 'registration.exams']));

        return $payment->fresh();
    }

    public function signature(array $payload, PaymentSetting $setting): string
    {
        $data = collect($payload)->except('CheckMacValue')->sortKeys(SORT_NATURAL | SORT_FLAG_CASE)->all();
        $query = urldecode(http_build_query($data));
        $raw = 'HashKey='.($setting->hashKey() ?: 'sandbox-key').'&'.$query.'&HashIV='.($setting->hashIv() ?: 'sandbox-iv');

        return strtoupper(hash('sha256', $raw));
    }

    public function verifySignature(array $payload, PaymentSetting $setting): bool
    {
        return hash_equals((string) ($payload['CheckMacValue'] ?? ''), $this->signature($payload, $setting));
    }

    public function log(RegistrationPayment $payment, string $event, ?string $old, ?string $new, ?int $adminId, ?string $ipAddress, array $payload = []): void
    {
        PaymentLog::query()->create([
            'registration_payment_id' => $payment->id,
            'student_registration_id' => $payment->student_registration_id,
            'event_type' => $event,
            'old_status' => $old,
            'new_status' => $new,
            'payload' => $this->safePayload($payload),
            'performed_by' => $adminId,
            'performed_ip' => $ipAddress,
        ]);
        Log::info('Payment event logged.', ['payment' => $payment->payment_reference, 'event' => $event]);
    }

    private function sendConfirmation(RegistrationPayment $payment): void
    {
        Mail::to($payment->registration->student_email)
            ->cc($payment->registration->contact?->parent_email)
            ->send(new PaymentConfirmationMail($payment));
    }

    private function gatewayPaymentMethod(string $method): string
    {
        return match ($method) {
            'atm' => 'ATM',
            default => 'Credit',
        };
    }

    private function safePayload(array $payload): array
    {
        return collect($payload)->except(['HashKey', 'HashIV', 'hash_key', 'hash_iv'])->all();
    }
}
