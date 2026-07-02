<?php

namespace App\Services\Payments;

use App\Models\PaymentSetting;
use App\Models\RegistrationPayment;

class NewebPayPaymentProvider implements PaymentGatewayProviderInterface
{
    public function key(): string
    {
        return 'newebpay';
    }

    public function createCheckoutPayload(RegistrationPayment $payment, PaymentSetting $setting): array
    {
        throw new \LogicException('NewebPay checkout adapter is not implemented yet. Add provider fields, AES/SHA256 trade info encryption, and sandbox verification before enabling.');
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
