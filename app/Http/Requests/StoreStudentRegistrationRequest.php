<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreStudentRegistrationRequest extends FormRequest
{
    private const PASSPORT_DRAFT_SESSION_KEY = 'student_registration_passport_drafts';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'family_name_en' => ['required_without:student_full_name', 'string', 'max:80'],
            'first_name_en' => ['required_without:student_full_name', 'string', 'max:80'],
            'middle_initial' => ['nullable', 'string', 'max:3'],
            'middle_name' => ['nullable', 'string', 'max:80'],
            'chinese_legal_name' => ['nullable', 'string', 'max:120'],
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
            'student_phone' => ['required', 'string', 'max:40', 'regex:/^\\+?[0-9\\s().-]{6,40}$/'],
            'current_school' => ['nullable', 'string', 'max:160'],
            'grade' => ['nullable', 'string', 'max:40'],
            'passport_file' => ['required_without:passport_file_token', 'nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'passport_file_token' => ['required_without:passport_file', 'nullable', 'string', 'regex:/^[A-Za-z0-9]{40}$/'],
            'school_name' => ['required', 'string', 'max:160'],
            'school_country' => ['required', 'string', 'max:80'],
            'school_city' => ['nullable', 'string', 'max:100'],
            'grade_level' => ['required', 'string', 'max:40'],
            'graduation_year' => ['nullable', 'integer', 'min:2026', 'max:2040'],
            'parent_first_name' => ['required_without:parent_full_name', 'string', 'max:80'],
            'parent_last_name' => ['required_without:parent_full_name', 'string', 'max:80'],
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
            'preparation_interest' => ['nullable', 'boolean'],
            'group_class_interest' => ['nullable', 'boolean'],
            'private_tutoring_interest' => ['nullable', 'boolean'],
            'preferred_tutoring_schedule' => ['nullable', 'string', 'max:160'],
            'preferred_tutoring_language' => ['nullable', 'string', 'max:40'],
            'preparation_notes' => ['nullable', 'string', 'max:1000'],
            'needs_accommodations' => ['nullable', 'boolean'],
            'ssd_code' => ['nullable', 'required_if:needs_accommodations,1', 'string', 'max:60'],
            'accommodation_status' => ['nullable', 'required_if:needs_accommodations,1', 'in:approved,pending,new'],
            'accommodations' => ['nullable', 'array'],
            'accommodations.*.exam' => ['nullable', 'string', 'max:120'],
            'accommodations.*.request' => ['nullable', 'string', 'max:180'],
            'payment_method' => ['nullable', 'in:bank_transfer,cash,online,manual_bank_transfer,credit_card,atm'],
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
            'passport_file.required_without' => __('student_registration.validation.passport_required'),
            'passport_file_token.required_without' => __('student_registration.validation.passport_required'),
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
            'school_name' => $this->input('school_name') ?: $this->input('current_school'),
            'school_city' => $this->input('school_city') ?: $this->input('mailing_city'),
            'grade_level' => $this->input('grade_level') ?: $this->input('grade'),
            'parent_full_name' => $this->input('parent_full_name') ?: $parentName,
            'practice_exam_total' => (int) $this->input('practice_exam_total', 0),
            'payment_method' => $this->input('payment_method') ?: 'bank_transfer',
        ]);
    }

    protected function failedValidation(Validator $validator): void
    {
        $passportDraft = $this->preservePassportDraft($validator);
        $input = $this->except(array_merge($this->dontFlash ?? [], ['passport_file']));

        if ($passportDraft) {
            $input['passport_file_token'] = $passportDraft['token'];
        }

        throw new HttpResponseException(
            redirect($this->getRedirectUrl())
                ->withInput($input)
                ->withErrors($validator, $this->errorBag)
                ->with('student_registration_error_step', $this->firstErrorStep($validator))
        );
    }

    private function preservePassportDraft(Validator $validator): ?array
    {
        if ($validator->errors()->has('passport_file')) {
            return null;
        }

        $existingToken = (string) $this->input('passport_file_token');
        $drafts = $this->session()->get(self::PASSPORT_DRAFT_SESSION_KEY, []);

        if ($existingToken !== '' && isset($drafts[$existingToken])) {
            return ['token' => $existingToken] + $drafts[$existingToken];
        }

        if (! $this->hasFile('passport_file') || ! $this->file('passport_file')?->isValid()) {
            return null;
        }

        $file = $this->file('passport_file');
        $token = Str::random(40);
        $extension = $file->getClientOriginalExtension() ?: 'upload';
        $path = $file->storeAs('registration-drafts/passports', $token.'.'.$extension, 'local');

        $drafts[$token] = [
            'path' => $path,
            'name' => basename($file->getClientOriginalName()),
            'mime' => $file->getMimeType(),
            'size' => $file->getSize(),
        ];

        $this->session()->put(self::PASSPORT_DRAFT_SESSION_KEY, $drafts);

        return ['token' => $token] + $drafts[$token];
    }

    private function firstErrorStep(Validator $validator): int
    {
        $field = (string) collect($validator->errors()->keys())->first();

        foreach ($this->stepFields() as $step => $patterns) {
            foreach ($patterns as $pattern) {
                if ($field === $pattern || Str::is($pattern.'.*', $field)) {
                    return $step;
                }
            }
        }

        return 1;
    }

    /**
     * @return array<int, array<int, string>>
     */
    private function stepFields(): array
    {
        return [
            1 => [
                'family_name_en', 'first_name_en', 'middle_initial', 'middle_name', 'chinese_legal_name',
                'student_full_name', 'preferred_name', 'gender', 'date_of_birth', 'nationality',
                'passport_number', 'passport_expiry_date', 'student_email', 'student_phone',
                'current_school', 'grade', 'passport_file', 'passport_file_token', 'school_name',
                'school_country', 'school_city', 'grade_level', 'graduation_year',
            ],
            2 => [
                'parent_first_name', 'parent_last_name', 'parent_full_name', 'relationship',
                'parent_email', 'parent_phone', 'mailing_address', 'mailing_city', 'postal_code',
                'emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relationship',
            ],
            3 => ['exam_subject_uuids', 'exam_subject_ids', 'practice_exams', 'practice_exam_total'],
            4 => [
                'needs_accommodations', 'ssd_code', 'accommodation_status', 'accommodations',
                'preparation_interest', 'group_class_interest', 'private_tutoring_interest',
                'preferred_tutoring_schedule', 'preferred_tutoring_language', 'preparation_notes',
            ],
            5 => [
                'payment_method', 'accurate_information', 'ap_policies', 'privacy_policy',
                'terms_conditions', 'confirmed_review',
            ],
        ];
    }
}
