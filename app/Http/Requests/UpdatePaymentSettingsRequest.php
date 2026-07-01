<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return [
            'provider' => ['required', 'in:manual,ecpay,newebpay'],
            'mode' => ['required', 'in:sandbox,production'],
            'merchant_id' => ['nullable', 'string', 'max:120'],
            'hash_key' => ['nullable', 'string', 'max:255'],
            'hash_iv' => ['nullable', 'string', 'max:255'],
            'callback_url' => ['nullable', 'url', 'max:255'],
            'return_url' => ['nullable', 'url', 'max:255'],
            'success_url' => ['nullable', 'url', 'max:255'],
            'failed_url' => ['nullable', 'url', 'max:255'],
            'bank_name' => ['nullable', 'string', 'max:120'],
            'bank_code' => ['nullable', 'string', 'max:20'],
            'account_name' => ['nullable', 'string', 'max:120'],
            'account_number' => ['nullable', 'string', 'max:80'],
            'manual_instruction' => ['nullable', 'string', 'max:2000'],
            'payment_deadline_days' => ['required', 'integer', 'min:1', 'max:60'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
