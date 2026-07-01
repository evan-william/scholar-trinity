{{ __('ap_registration.email.greeting', ['name' => $payment->registration->student_full_name]) }}

Payment Reference: {{ $payment->payment_reference }}
Amount Due: {{ $payment->currency }} {{ number_format($payment->grand_total) }}
Exam Fee: {{ $payment->currency }} {{ number_format($payment->exam_fee_amount) }}
Service Fee: {{ $payment->currency }} {{ number_format($payment->service_fee_amount) }}
Late Fee: {{ $payment->currency }} {{ number_format($payment->late_fee_amount) }}
Bank: {{ $setting->bank_name }} ({{ $setting->bank_code }})
Account: {{ $setting->account_name }} / {{ $setting->account_number }}
Deadline: {{ optional($payment->payment_deadline_at)->format('Y-m-d H:i') }}

{{ $setting->manual_instruction }}
{{ __('ap_registration.email.footer') }}
