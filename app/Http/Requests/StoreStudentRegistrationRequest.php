<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class StoreStudentRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'family_name_en' => ['nullable', 'string', 'max:80'],
            'first_name_en' => ['nullable', 'string', 'max:80'],
            'middle_initial' => ['nullable', 'string', 'max:3'],
            'middle_name' => ['nullable', 'string', 'max:80'],
            'chinese_legal_name' => ['nullable', 'string', 'max:80'],
            'student_full_name' => ['required', 'string', 'max:140'],
            'preferred_name' => ['nullable', 'string', 'max:100'],
            'gender' => ['nullable', 'in:Female,Male,Non-binary,Prefer not to say'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'nationality' => ['required', 'string', 'max:80'],
            'passport_number' => [
                'required',
                'string',
                'max:30',
                'regex:/^[A-Z0-9][A-Z0-9\\-]{4,29}$/i',
                Rule::unique('student_registrations', 'passport_number')->whereNull('deleted_at'),
            ],
            'passport_expiry_date' => ['nullable', 'date', 'after:today'],
            'student_email' => [
                'required',
                'email',
                'max:160',
                Rule::unique('student_registrations', 'student_email')->whereNull('deleted_at'),
            ],
            'student_phone' => ['nullable', 'string', 'max:40', 'regex:/^\\+?[0-9\\s().-]{6,40}$/'],
            'current_school' => ['nullable', 'string', 'max:160'],
            'grade' => ['nullable', 'string', 'max:40'],
            'passport_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'school_name' => ['required', 'string', 'max:160'],
            'school_country' => ['required', 'string', 'max:80'],
            'school_city' => ['nullable', 'string', 'max:100'],
            'grade_level' => ['required', 'string', 'max:40'],
            'graduation_year' => ['nullable', 'integer', 'min:2026', 'max:2040'],
            'parent_first_name' => ['nullable', 'string', 'max:80'],
            'parent_last_name' => ['nullable', 'string', 'max:80'],
            'mailing_address' => ['nullable', 'string', 'max:255'],
            'mailing_city' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:12'],
            'parent_full_name' => ['required', 'string', 'max:140'],
            'relationship' => ['required', 'string', 'max:60'],
            'parent_email' => ['required', 'email', 'max:160'],
            'parent_phone' => ['required', 'string', 'max:40', 'regex:/^\\+?[0-9\\s().-]{6,40}$/'],
            'emergency_contact_name' => ['required', 'string', 'max:140'],
            'emergency_contact_phone' => ['required', 'string', 'max:40', 'regex:/^\\+?[0-9\\s().-]{6,40}$/'],
            'emergency_contact_relationship' => ['required', 'string', 'max:60'],
            'exam_subject_uuids' => ['required_without:exam_subject_ids', 'array', 'min:1'],
            'exam_subject_uuids.*' => ['uuid', 'distinct', Rule::exists('ap_exam_subjects', 'uuid')->whereNull('deleted_at')],
            'exam_subject_ids' => ['required_without:exam_subject_uuids', 'array', 'min:1'],
            'exam_subject_ids.*' => ['integer', 'distinct', Rule::exists('ap_exam_subjects', 'id')->whereNull('deleted_at')],
            'practice_exams' => ['nullable', 'array'],
            'practice_exams.*' => ['string', 'max:120'],
            'practice_exam_total' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'needs_accommodations' => ['nullable', 'boolean'],
            'ssd_code' => ['nullable', 'string', 'max:60'],
            'accommodation_status' => ['nullable', 'string', 'max:40'],
            'accommodations' => ['nullable', 'array'],
            'accommodations.*.exam' => ['nullable', 'string', 'max:120'],
            'accommodations.*.request' => ['nullable', 'string', 'max:180'],
            'payment_method' => ['nullable', 'in:bank_transfer,cash,online,manual_bank_transfer,credit_card'],
            'accurate_information' => ['accepted'],
            'ap_policies' => ['accepted'],
            'privacy_policy' => ['accepted'],
            'terms_conditions' => ['accepted'],
            'confirmed_review' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'passport_number.regex' => __('student_registration.validation.passport_format'),
            'student_email.unique' => __('student_registration.validation.duplicate_email'),
            'passport_number.unique' => __('student_registration.validation.duplicate_passport'),
            'exam_subject_ids.required_without' => __('student_registration.validation.exam_required'),
            'exam_subject_uuids.required_without' => __('student_registration.validation.exam_required'),
            'confirmed_review.accepted' => __('ap_registration.form.review_form'),
        ];
    }

    protected function prepareForValidation(): void
    {
        $studentName = trim(collect([
            $this->input('family_name_en'),
            $this->input('first_name_en'),
            $this->input('middle_name'),
        ])->filter()->implode(' '));

        $parentName = trim(collect([
            $this->input('parent_first_name'),
            $this->input('parent_last_name'),
        ])->filter()->implode(' '));

        $this->merge([
            'student_full_name' => $this->input('student_full_name') ?: $studentName,
            'preferred_name' => $this->input('preferred_name') ?: $this->input('chinese_legal_name'),
            'date_of_birth' => $this->input('date_of_birth') ?: '2000-01-01',
            'nationality' => $this->input('nationality') ?: 'Not provided',
            'passport_number' => $this->input('passport_number') ?: 'PEND'.Str::upper(Str::random(10)),
            'school_name' => $this->input('school_name') ?: $this->input('current_school'),
            'school_country' => $this->input('school_country') ?: 'Taiwan',
            'school_city' => $this->input('school_city') ?: $this->input('mailing_city'),
            'grade_level' => $this->input('grade_level') ?: $this->input('grade'),
            'parent_full_name' => $this->input('parent_full_name') ?: $parentName,
            'relationship' => $this->input('relationship') ?: 'Parent / Guardian',
            'emergency_contact_relationship' => $this->input('emergency_contact_relationship') ?: 'Emergency contact',
            'practice_exam_total' => (int) $this->input('practice_exam_total', 0),
            'payment_method' => $this->input('payment_method') ?: 'bank_transfer',
        ]);
    }
}
