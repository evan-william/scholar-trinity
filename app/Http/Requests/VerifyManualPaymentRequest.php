<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyManualPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return [
            'action' => ['required', 'in:verify,reject'],
            'note' => ['nullable', 'string', 'max:1000'],
            'rejected_reason' => ['required_if:action,reject', 'nullable', 'string', 'max:1000'],
        ];
    }
}
