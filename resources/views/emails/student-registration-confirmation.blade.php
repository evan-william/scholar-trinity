<x-emails.layout>
<p>{{ __('ap_registration.email.greeting', ['name' => $registration->student_full_name]) }}</p>
<p>{{ __('ap_registration.email.registration_received') }}</p>
<table class="meta">
<tr><td>Registration Reference</td><td>{{ $registration->registration_number }}</td></tr>
<tr><td>Student</td><td>{{ $registration->student_full_name }}</td></tr>
<tr><td>Submission Date</td><td>{{ optional($registration->submitted_at)->format('Y-m-d H:i') }}</td></tr>
<tr><td>Payment</td><td>Open the registration page for bank-transfer instructions and payment-proof upload.</td></tr>
</table>
<p><strong>Selected Exams</strong></p>
<ul>@foreach ($registration->exams as $exam)<li>{{ $exam->name }} - {{ optional(\Illuminate\Support\Carbon::parse($exam->pivot->exam_date))->format('Y-m-d') }}</li>@endforeach</ul>
<p>{{ __('ap_registration.email.next_steps') }}</p>
</x-emails.layout>
