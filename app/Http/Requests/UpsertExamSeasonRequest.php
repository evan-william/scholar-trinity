<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpsertExamSeasonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    public function rules(): array
    {
        return [
            'season_name' => ['required', 'string', 'max:160'],
            'academic_year' => ['required', 'string', 'max:40'],
            'exam_year' => ['required', 'integer', 'min:2020', 'max:2100'],
            'main_registration_start_at' => ['nullable', 'date'],
            'main_registration_end_at' => ['nullable', 'date', 'after_or_equal:main_registration_start_at'],
            'late_registration_start_at' => ['nullable', 'date'],
            'late_registration_end_at' => ['nullable', 'date', 'after_or_equal:late_registration_start_at'],
            'timezone' => ['required', 'string', 'max:80'],
            'currency' => ['required', 'string', 'max:8'],
            'default_service_fee' => ['required', 'integer', 'min:0', 'max:999999'],
            'default_late_fee' => ['required', 'integer', 'min:0', 'max:999999'],
            'status' => ['required', 'in:draft,not_open,open,late_registration,closed,archived'],
            'is_active' => ['nullable', 'boolean'],
            'public_status_message' => ['nullable', 'string', 'max:1000'],
            'close_reason' => ['nullable', 'string', 'max:1000'],
            'reopen_reason' => ['nullable', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
