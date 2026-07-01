<x-emails.layout>
<p>{{ __('ap_registration.email.greeting', ['name' => $payment->registration->student_full_name]) }}</p>
<p>Please complete payment using the details below.</p>
<table class="meta">
<tr><td>Registration Reference</td><td>{{ $payment->registration->registration_number }}</td></tr>
<tr><td>Payment Reference</td><td>{{ $payment->payment_reference }}</td></tr>
<tr><td>Amount Due</td><td>{{ $payment->currency }} {{ number_format($payment->grand_total) }}</td></tr>
<tr><td>Exam Fee</td><td>{{ $payment->currency }} {{ number_format($payment->exam_fee_amount) }}</td></tr>
<tr><td>Service Fee</td><td>{{ $payment->currency }} {{ number_format($payment->service_fee_amount) }}</td></tr>
<tr><td>Late Fee</td><td>{{ $payment->currency }} {{ number_format($payment->late_fee_amount) }}</td></tr>
<tr><td>Bank</td><td>{{ $setting->bank_name }} ({{ $setting->bank_code }})</td></tr>
<tr><td>Account</td><td>{{ $setting->account_name }} / {{ $setting->account_number }}</td></tr>
<tr><td>Deadline</td><td>{{ optional($payment->payment_deadline_at)->format('Y-m-d H:i') }}</td></tr>
</table>
<p>{{ $setting->manual_instruction }}</p>
</x-emails.layout>
