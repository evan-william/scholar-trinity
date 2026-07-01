<x-emails.layout>
<p>{{ __('ap_registration.email.greeting', ['name' => $payment->registration->student_full_name]) }}</p>
<p>Your AP Exam payment status has been updated.</p>
<table class="meta">
<tr><td>Registration Reference</td><td>{{ $payment->registration->registration_number }}</td></tr>
<tr><td>Payment Status</td><td>{{ $payment->payment_status }}</td></tr>
<tr><td>Paid Amount</td><td>{{ $payment->currency }} {{ number_format($payment->grand_total) }}</td></tr>
<tr><td>Payment Method</td><td>{{ str_replace('_', ' ', $payment->payment_method) }}</td></tr>
<tr><td>Transaction ID</td><td>{{ $payment->transaction_id ?: '-' }}</td></tr>
<tr><td>Payment Date</td><td>{{ optional($payment->paid_at)->format('Y-m-d H:i') ?: '-' }}</td></tr>
</table>
<p><strong>Selected Exams</strong></p>
<ul>@foreach ($payment->registration->exams as $exam)<li>{{ $exam->name }} - {{ optional(\Illuminate\Support\Carbon::parse($exam->pivot->exam_date))->format('Y-m-d') }}</li>@endforeach</ul>
</x-emails.layout>
