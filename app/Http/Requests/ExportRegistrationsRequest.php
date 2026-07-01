<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExportRegistrationsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:120'],
            'status' => ['nullable', 'string', 'max:40'],
            'payment_status' => ['nullable', 'string', 'max:40'],
            'period' => ['nullable', 'in:main,late'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'subject_id' => ['nullable', 'integer', 'exists:ap_exam_subjects,id'],
            'document_status' => ['nullable', 'string', 'max:40'],
            'verification_status' => ['nullable', 'string', 'max:40'],
            'school' => ['nullable', 'string', 'max:120'],
            'format' => ['nullable', 'in:csv,xlsx,xls'],
            'template' => ['nullable', 'in:standard,tpca,school'],
            'include_notes' => ['nullable', 'boolean'],
            'mask_passport' => ['nullable', 'boolean'],
        ];
    }
}
