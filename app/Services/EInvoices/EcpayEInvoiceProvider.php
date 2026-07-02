<?php

namespace App\Services\EInvoices;

use App\Models\EInvoiceSetting;
use App\Models\EInvoiceTransaction;
use App\Models\ReceiptRequest;

class EcpayEInvoiceProvider implements EInvoiceProviderInterface
{
    public function key(): string
    {
        return 'ecpay';
    }

    public function issue(ReceiptRequest $receipt, EInvoiceSetting $setting): EInvoiceTransaction
    {
        return EInvoiceTransaction::query()->create([
            'receipt_request_id' => $receipt->id,
            'provider' => 'ecpay',
            'provider_status' => 'failed',
            'provider_transaction_id' => 'ECPAY-PENDING-'.$receipt->uuid,
            'request_payload' => [
                'amount' => $receipt->taxable_receipt_amount,
                'buyer_email' => $receipt->buyer_email,
            ],
            'response_payload' => ['placeholder' => true],
            'error_message' => 'ECPay e-invoice adapter is not implemented yet. Add merchant credentials, issue/cancel/resend API calls, and sandbox verification before enabling.',
        ]);
    }
}
