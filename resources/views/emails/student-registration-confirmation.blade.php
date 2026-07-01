<x-emails.layout>
<p>{{ __('ap_registration.email.greeting', ['name' => $registration->student_full_name]) }}</p>
<p>{{ __('ap_registration.email.registration_received') }}</p>
<table class="meta">
<tr><td>Registration Reference</td><td>{{ $registration->registration_number }}</td></tr>
<tr><td>Student</td><td>{{ $registration->student_full_name }}</td></tr>
<tr><td>Submission Date</td><td>{{ optional($registration->submitted_at)->format('Y-m-d H:i') }}</td></tr>
<tr><td>Exam Fee</td><td>{{ $registration->currency ?: 'NTD' }} {{ number_format($registration->exam_fee_total) }}</td></tr>
<tr><td>Service Fee</td><td>{{ $registration->currency ?: 'NTD' }} {{ number_format($registration->service_fee_total) }}</td></tr>
<tr><td>Late Fee</td><td>{{ $registration->currency ?: 'NTD' }} {{ number_format($registration->late_fee_total) }}</td></tr>
<tr><td>Total</td><td>{{ $registration->currency ?: 'NTD' }} {{ number_format($registration->grand_total ?: $registration->total_fee) }}</td></tr>
</table>
<p><strong>Selected Exams</strong></p>
<ul>@foreach ($registration->exams as $exam)<li>{{ $exam->name }} - {{ optional(\Illuminate\Support\Carbon::parse($exam->pivot->exam_date))->format('Y-m-d') }}</li>@endforeach</ul>
<p>{{ __('ap_registration.email.next_steps') }}</p>
</x-emails.layout>
