<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEInvoiceSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return [
            'provider' => ['required', 'in:manual,ecpay,newebpay,ezpay,turnkey'],
            'environment' => ['required', 'in:sandbox,production'],
            'merchant_id' => ['nullable', 'string', 'max:120'],
            'api_key' => ['nullable', 'string', 'max:255'],
            'hash_key' => ['nullable', 'string', 'max:255'],
            'hash_iv' => ['nullable', 'string', 'max:255'],
            'callback_url' => ['nullable', 'url', 'max:255'],
            'late_fee_taxable' => ['nullable', 'boolean'],
            'allow_unpaid_receipts' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
