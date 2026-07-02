<?php

namespace App\Services\Payments;

use App\Models\PaymentSetting;
use App\Models\RegistrationPayment;

class EcpayPaymentProvider implements PaymentGatewayProviderInterface
{
    public function key(): string
    {
        return 'ecpay';
    }

    public function createCheckoutPayload(RegistrationPayment $payment, PaymentSetting $setting): array
    {
        $orderId = $payment->gateway_order_id ?: 'AP'.now()->format('YmdHis').$payment->id;
        $payment->update([
            'provider' => 'ecpay',
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
            'ChoosePayment' => $this->paymentMethod($payment->payment_method),
        ];
        $payload['CheckMacValue'] = $this->signature($payload, $setting);

        return $payload;
    }

    public function verifyCallback(array $payload, PaymentSetting $setting): bool
    {
        return hash_equals((string) ($payload['CheckMacValue'] ?? ''), $this->signature($payload, $setting));
    }

    public function isSuccessfulCallback(array $payload): bool
    {
        return (string) ($payload['RtnCode'] ?? $payload['status'] ?? '') === '1';
    }

    public function transactionId(array $payload): ?string
    {
        return (string) ($payload['TradeNo'] ?? $payload['transaction_id'] ?? '') ?: null;
    }

    public function signature(array $payload, PaymentSetting $setting): string
    {
        $data = collect($payload)->except('CheckMacValue')->sortKeys(SORT_NATURAL | SORT_FLAG_CASE)->all();
        $query = urldecode(http_build_query($data));
        $raw = 'HashKey='.($setting->hashKey() ?: 'sandbox-key').'&'.$query.'&HashIV='.($setting->hashIv() ?: 'sandbox-iv');

        return strtoupper(hash('sha256', $raw));
    }

    private function paymentMethod(string $method): string
    {
        return match ($method) {
            'atm' => 'ATM',
            default => 'Credit',
        };
    }
}
