{{ __('ap_registration.email.greeting', ['name' => $payment->registration->student_full_name]) }}

Payment Status: {{ $payment->payment_status }}
Registration Reference: {{ $payment->registration->registration_number }}
Paid Amount: {{ $payment->currency }} {{ number_format($payment->grand_total) }}
Payment Method: {{ str_replace('_', ' ', $payment->payment_method) }}
Transaction ID: {{ $payment->transaction_id ?: '-' }}
Payment Date: {{ optional($payment->paid_at)->format('Y-m-d H:i') ?: '-' }}

{{ __('ap_registration.email.footer') }}
