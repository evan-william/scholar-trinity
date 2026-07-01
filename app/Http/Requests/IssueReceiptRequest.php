<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IssueReceiptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return [
            'receipt_number' => ['required', 'string', 'max:80'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
