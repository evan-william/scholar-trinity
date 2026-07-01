<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return [
            'verification_status' => ['required', 'in:unverified,verified,needs_review,rejected'],
            'verification_note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
