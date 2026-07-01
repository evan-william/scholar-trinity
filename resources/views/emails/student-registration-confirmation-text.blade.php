{{ __('ap_registration.email.greeting', ['name' => $registration->student_full_name]) }}

{{ __('ap_registration.email.registration_received') }}

Registration Reference: {{ $registration->registration_number }}
Student: {{ $registration->student_full_name }}
Submission Date: {{ optional($registration->submitted_at)->format('Y-m-d H:i') }}
Selected Exams: {{ $registration->exams->pluck('name')->join(', ') }}
Total: {{ $registration->currency ?: 'NTD' }} {{ number_format($registration->grand_total ?: $registration->total_fee) }}

{{ __('ap_registration.email.next_steps') }}
{{ __('ap_registration.email.footer') }}
