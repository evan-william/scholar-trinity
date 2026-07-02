<?php

namespace App\Services\Payments;

use App\Models\PaymentSetting;
use App\Models\RegistrationPayment;

class ManualPaymentProvider implements PaymentGatewayProviderInterface
{
    public function key(): string
    {
        return 'manual';
    }

    public function createCheckoutPayload(RegistrationPayment $payment, PaymentSetting $setting): array
    {
        return [
            'provider' => 'manual',
            'payment_reference' => $payment->payment_reference,
            'amount' => $payment->grand_total,
            'currency' => $payment->currency,
            'bank_name' => $setting->bank_name,
            'bank_code' => $setting->bank_code,
            'account_name' => $setting->account_name,
            'account_number' => $setting->account_number,
        ];
    }

    public function verifyCallback(array $payload, PaymentSetting $setting): bool
    {
        return false;
    }

    public function isSuccessfulCallback(array $payload): bool
    {
        return false;
    }

    public function transactionId(array $payload): ?string
    {
        return null;
    }
}
