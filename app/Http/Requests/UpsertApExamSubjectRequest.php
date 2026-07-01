<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpsertApExamSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $subjectId = $this->route('ap_exam_subject')?->id;

        return [
            'exam_season_id' => ['nullable', 'exists:exam_seasons,id'],
            'code' => ['required', 'string', 'max:40', Rule::unique('ap_exam_subjects', 'code')->ignore($subjectId)],
            'name' => ['required', 'string', 'max:160'],
            'category' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'exam_date' => ['nullable', 'date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after:start_time'],
            'timezone' => ['required', 'string', 'max:80'],
            'location' => ['nullable', 'string', 'max:160'],
            'quota' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'exam_fee' => ['required', 'integer', 'min:0', 'max:999999'],
            'service_fee' => ['required', 'integer', 'min:0', 'max:999999'],
            'late_registration_fee' => ['required', 'integer', 'min:0', 'max:999999'],
            'currency' => ['required', 'string', 'max:8'],
            'status' => ['required', 'in:draft,not_open,open,limited,full,closed,cancelled,disabled'],
            'registration_open_at' => ['nullable', 'date'],
            'registration_close_at' => ['nullable', 'date', 'after_or_equal:registration_open_at'],
            'late_registration_start_at' => ['nullable', 'date'],
            'late_registration_end_at' => ['nullable', 'date', 'after_or_equal:late_registration_start_at'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
