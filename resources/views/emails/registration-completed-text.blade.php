{{ __('ap_registration.email.greeting', ['name' => $registration->student_full_name]) }}

Your AP Exam registration is complete.
您的 AP 考試報名已完成。

Registration Reference: {{ $registration->registration_number }}
Status: {{ $registration->status }}
Payment Status: {{ $registration->payment_status }}
Verification Status: {{ $registration->verification_status }}
Student: {{ $registration->student_full_name }}

Selected Exams:
@foreach ($registration->exams as $exam)
- {{ $exam->name }} - {{ optional(\Illuminate\Support\Carbon::parse($exam->pivot->exam_date))->format('Y-m-d') }}
@endforeach

Please keep this email for your records.

{{ __('ap_registration.email.footer') }}
