<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateManagedRegistrationRequest extends FormRequest
{
    private array $criticalFields = ['passport_number', 'student_email', 'exam_subject_uuids', 'payment_status', 'payment_amount'];

    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        $registration = $this->route('studentRegistration');
        $criticalChanged = collect($this->criticalFields)->contains(function (string $field) use ($registration): bool {
            if ($field === 'exam_subject_uuids') {
                return $this->has($field);
            }

            return $this->has($field) && (string) $this->input($field) !== (string) $registration?->{$field};
        });

        return [
            'family_name_en' => ['nullable', 'string', 'max:80'],
            'first_name_en' => ['nullable', 'string', 'max:80'],
            'middle_initial' => ['nullable', 'string', 'max:3'],
            'middle_name' => ['nullable', 'string', 'max:80'],
            'chinese_legal_name' => ['nullable', 'string', 'max:120'],
            'student_full_name' => ['required', 'string', 'max:140'],
            'preferred_name' => ['nullable', 'string', 'max:100'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'nationality' => ['required', 'string', 'max:80'],
            'passport_number' => ['required', 'string', 'max:30', 'regex:/^[A-Z0-9][A-Z0-9\\-]{4,29}$/i'],
            'student_email' => ['required', 'email', 'max:160'],
            'student_phone' => ['nullable', 'string', 'max:40'],
            'school_name' => ['required', 'string', 'max:160'],
            'school_country' => ['required', 'string', 'max:80'],
            'school_city' => ['nullable', 'string', 'max:100'],
            'grade_level' => ['required', 'string', 'max:40'],
            'parent_first_name' => ['nullable', 'string', 'max:80'],
            'parent_last_name' => ['nullable', 'string', 'max:80'],
            'parent_full_name' => ['required', 'string', 'max:140'],
            'relationship' => ['required', 'string', 'max:60'],
            'parent_email' => ['required', 'email', 'max:160'],
            'parent_phone' => ['required', 'string', 'max:40'],
            'mailing_address' => ['nullable', 'string', 'max:255'],
            'mailing_city' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:12'],
            'emergency_contact_name' => ['required', 'string', 'max:140'],
            'emergency_contact_phone' => ['required', 'string', 'max:40'],
            'emergency_contact_relationship' => ['required', 'string', 'max:60'],
            'needs_accommodations' => ['nullable', 'boolean'],
            'ssd_code' => ['nullable', 'required_if:needs_accommodations,1', 'string', 'max:60'],
            'accommodation_status' => ['nullable', 'required_if:needs_accommodations,1', 'in:approved,pending,new'],
            'accommodations_payload' => ['nullable', 'array'],
            'status' => ['required', 'in:submitted,pending_payment,paid,cancelled,expired,draft'],
            'payment_status' => ['required', 'in:unpaid,pending_payment,waiting_verification,paid,failed,refunded,cancelled'],
            'payment_method' => ['nullable', 'string', 'max:80'],
            'payment_reference' => ['nullable', 'string', 'max:120'],
            'payment_date' => ['nullable', 'date'],
            'payment_amount' => ['nullable', 'integer', 'min:0'],
            'exam_subject_uuids' => ['nullable', 'array'],
            'exam_subject_uuids.*' => ['uuid', 'distinct', Rule::exists('ap_exam_subjects', 'uuid')->whereNull('deleted_at')],
            'reason' => [$criticalChanged ? 'required' : 'nullable', 'string', 'max:500'],
        ];
    }
}
