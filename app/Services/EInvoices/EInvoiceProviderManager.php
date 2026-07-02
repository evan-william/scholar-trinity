<?php

namespace App\Services\EInvoices;

use App\Models\EInvoiceSetting;

class EInvoiceProviderManager
{
    public function forSetting(EInvoiceSetting $setting): EInvoiceProviderInterface
    {
        return match ($setting->provider) {
            'ecpay' => app(EcpayEInvoiceProvider::class),
            'newebpay' => app(NewebPayEInvoiceProvider::class),
            default => app(ManualEInvoiceProvider::class),
        };
    }
}
