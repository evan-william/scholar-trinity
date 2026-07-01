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
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
