<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRegistrationStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:submitted,pending_payment,paid,cancelled,expired'],
            'note' => ['nullable', 'string', 'max:500'],
        ];
    }
}
