<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePassportStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:verified,invalid'],
            'verification_note' => ['nullable', 'string', 'max:1000'],
            'invalid_reason' => ['required_if:status,invalid', 'nullable', 'string', 'max:1000'],
        ];
    }
}
