<?php

namespace App\Services\Payments;

use App\Models\PaymentSetting;
use App\Models\RegistrationPayment;

interface PaymentGatewayProviderInterface
{
    public function key(): string;

    /**
     * @return array<string, mixed>
     */
    public function createCheckoutPayload(RegistrationPayment $payment, PaymentSetting $setting): array;

    /**
     * @param array<string, mixed> $payload
     */
    public function verifyCallback(array $payload, PaymentSetting $setting): bool;

    /**
     * @param array<string, mixed> $payload
     */
    public function isSuccessfulCallback(array $payload): bool;

    /**
     * @param array<string, mixed> $payload
     */
    public function transactionId(array $payload): ?string;
}
