<?php

namespace App\Services\EInvoices;

use App\Models\EInvoiceSetting;
use App\Models\EInvoiceTransaction;
use App\Models\ReceiptRequest;

class ManualEInvoiceProvider implements EInvoiceProviderInterface
{
    public function key(): string
    {
        return 'manual';
    }

    public function issue(ReceiptRequest $receipt, EInvoiceSetting $setting): EInvoiceTransaction
    {
        return EInvoiceTransaction::query()->create([
            'receipt_request_id' => $receipt->id,
            'provider' => $setting->provider,
            'provider_status' => $receipt->buyer_email ? 'issued' : 'failed',
            'provider_invoice_number' => $receipt->buyer_email ? 'FA'.now()->format('Ymd').str_pad((string) $receipt->id, 4, '0', STR_PAD_LEFT) : null,
            'provider_random_code' => $receipt->buyer_email ? (string) random_int(1000, 9999) : null,
            'provider_transaction_id' => 'EINV-'.$receipt->uuid,
            'request_payload' => [
                'amount' => $receipt->taxable_receipt_amount,
                'buyer_email' => $receipt->buyer_email,
            ],
            'response_payload' => ['manual_sandbox' => true],
            'error_message' => $receipt->buyer_email ? null : 'Missing buyer email.',
            'issued_at' => $receipt->buyer_email ? now() : null,
        ]);
    }
}
