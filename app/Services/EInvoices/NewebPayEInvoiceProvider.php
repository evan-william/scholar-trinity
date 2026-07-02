<?php

namespace App\Services\EInvoices;

use App\Models\EInvoiceSetting;
use App\Models\EInvoiceTransaction;
use App\Models\ReceiptRequest;

class NewebPayEInvoiceProvider implements EInvoiceProviderInterface
{
    public function key(): string
    {
        return 'newebpay';
    }

    public function issue(ReceiptRequest $receipt, EInvoiceSetting $setting): EInvoiceTransaction
    {
        return EInvoiceTransaction::query()->create([
            'receipt_request_id' => $receipt->id,
            'provider' => 'newebpay',
            'provider_status' => 'failed',
            'provider_transaction_id' => 'NEWEBPAY-PENDING-'.$receipt->uuid,
            'request_payload' => [
                'amount' => $receipt->taxable_receipt_amount,
                'buyer_email' => $receipt->buyer_email,
            ],
            'response_payload' => ['placeholder' => true],
            'error_message' => 'NewebPay e-invoice adapter is not implemented yet. Confirm provider support and API fields before enabling.',
        ]);
    }
}
