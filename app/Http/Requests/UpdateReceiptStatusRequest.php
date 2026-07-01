<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReceiptStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:requested,pending_issue,issued,sent,failed,cancelled,voided'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
