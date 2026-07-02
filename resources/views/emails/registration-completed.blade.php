<x-emails.layout>
<p>{{ __('ap_registration.email.greeting', ['name' => $registration->student_full_name]) }}</p>
<p>Your AP Exam registration is complete. Payment and coordinator verification have both been recorded.</p>
<p>您的 AP 考試報名已完成。付款與協調員審核皆已確認。</p>
<table class="meta">
<tr><td>Registration Reference</td><td>{{ $registration->registration_number }}</td></tr>
<tr><td>Status</td><td>{{ $registration->status }}</td></tr>
<tr><td>Payment Status</td><td>{{ $registration->payment_status }}</td></tr>
<tr><td>Verification Status</td><td>{{ $registration->verification_status }}</td></tr>
<tr><td>Student</td><td>{{ $registration->student_full_name }}</td></tr>
</table>
<p><strong>Selected Exams / 已選科目</strong></p>
<ul>
@foreach ($registration->exams as $exam)
<li>{{ $exam->name }} - {{ optional(\Illuminate\Support\Carbon::parse($exam->pivot->exam_date))->format('Y-m-d') }}</li>
@endforeach
</ul>
<p>Please keep this email for your records. Further schedule or test-day instructions will be shared by the coordinator when available.</p>
</x-emails.layout>
