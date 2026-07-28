<?php

namespace App\Http\Requests;

class AdminUpdateReceiptRequest extends StoreReceiptRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return parent::rules() + [
            'invoice_number' => ['nullable', 'string', 'max:80'],
            'receipt_number' => ['nullable', 'string', 'max:80'],
            'invoice_received' => ['nullable', 'boolean'],
            'receipt_received' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
