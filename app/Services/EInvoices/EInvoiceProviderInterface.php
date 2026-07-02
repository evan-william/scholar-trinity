<?php

namespace App\Services\EInvoices;

use App\Models\EInvoiceSetting;
use App\Models\EInvoiceTransaction;
use App\Models\ReceiptRequest;

interface EInvoiceProviderInterface
{
    public function key(): string;

    public function issue(ReceiptRequest $receipt, EInvoiceSetting $setting): EInvoiceTransaction;
}
