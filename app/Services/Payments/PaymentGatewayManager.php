<?php

namespace App\Services\Payments;

use App\Models\PaymentSetting;

class PaymentGatewayManager
{
    public function forSetting(PaymentSetting $setting): PaymentGatewayProviderInterface
    {
        return match ($setting->provider) {
            'ecpay' => app(EcpayPaymentProvider::class),
            'newebpay' => app(NewebPayPaymentProvider::class),
            default => app(ManualPaymentProvider::class),
        };
    }
}
