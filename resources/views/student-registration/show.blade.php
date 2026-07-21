<x-public-flow-shell
    :title="__('student_registration.successful').' | '.$registration->registration_number"
    heading="Registration Submitted"
    subtitle="The AP registration form has been received. Payment and admin verification are still required before the registration is complete."
    badge="Submitted"
>
    <section class="card">
        <h2>Registration Reference</h2>
        <table class="summary-table">
            <tr><td>Reference Number</td><td><strong>{{ $registration->registration_number }}</strong></td></tr>
            <tr><td>Status</td><td><span class="status {{ $registration->status }}">{{ str_replace('_', ' ', $registration->status) }}</span></td></tr>
            <tr><td>Submitted At</td><td>{{ optional($registration->submitted_at)->format('Y-m-d H:i') ?: '-' }}</td></tr>
            <tr><td>Confirmation Email</td><td>{{ $registration->student_email }}@if($registration->contact?->parent_email)<br>{{ $registration->contact->parent_email }}@endif</td></tr>
        </table>
        <div class="actions">
            <a class="btn gold" href="{{ route('payments.show', $registration->registration_number) }}">Continue to Payment</a>
            <a class="btn light" href="{{ route('landing') }}">Back to Landing</a>
        </div>
    </section>

    <section class="grid-2">
        <div class="card">
            <h2>Student Information</h2>
            <table class="summary-table">
                <tr><td>Student</td><td>{{ $registration->student_full_name }}</td></tr>
                <tr><td>Legal Name</td><td>{{ collect([$registration->family_name_en, $registration->first_name_en, $registration->middle_name])->filter()->implode(' ') ?: '-' }} @if($registration->chinese_legal_name)<br>{{ $registration->chinese_legal_name }}@endif</td></tr>
                <tr><td>Date of Birth</td><td>{{ optional($registration->date_of_birth)->format('Y-m-d') ?: '-' }}</td></tr>
                <tr><td>Nationality</td><td>{{ $registration->nationality ?: '-' }}</td></tr>
                <tr><td>School</td><td>{{ $registration->school_name ?: '-' }}</td></tr>
                <tr><td>Grade</td><td>{{ $registration->grade_level ?: '-' }}</td></tr>
                <tr><td>Passport</td><td>{{ $registration->passport_number ?: '-' }}<br>{{ str_replace('_', ' ', $registration->passport_upload_status) }}</td></tr>
            </table>
        </div>

        <div class="card">
            <h2>Parent and Emergency Contact</h2>
            <table class="summary-table">
                <tr><td>Parent</td><td>{{ $registration->contact?->parent_full_name ?: '-' }}</td></tr>
                <tr><td>Relationship</td><td>{{ $registration->contact?->relationship ?: '-' }}</td></tr>
                <tr><td>Parent Email</td><td>{{ $registration->contact?->parent_email ?: '-' }}</td></tr>
                <tr><td>Parent Phone</td><td>{{ $registration->contact?->parent_phone ?: '-' }}</td></tr>
                <tr><td>Mailing Address</td><td>{{ collect([$registration->contact?->mailing_address, $registration->contact?->mailing_city, $registration->contact?->postal_code])->filter()->implode(', ') ?: '-' }}</td></tr>
                <tr><td>Emergency Contact</td><td>{{ $registration->contact?->emergency_contact_name ?: '-' }} @if($registration->contact?->emergency_contact_phone)<br>{{ $registration->contact->emergency_contact_phone }}@endif @if($registration->contact?->emergency_contact_relationship)<br>{{ $registration->contact->emergency_contact_relationship }}@endif</td></tr>
            </table>
        </div>
    </section>

    <section class="grid-2">
        <div class="card">
            <h2>Exam Selection</h2>
            <table class="summary-table">
                <tr><td>Selected AP Exams</td><td>{{ $registration->exams->pluck('name')->join(', ') ?: '-' }}</td></tr>
                <tr><td>Practice Exams</td><td>{{ $registration->practiceExamSelections->pluck('exam_name')->join(', ') ?: '-' }}</td></tr>
                <tr><td>Accommodations</td><td>{{ $registration->needs_accommodations ? 'Requested' : 'Not requested' }} @if($registration->ssd_code)<br>SSD {{ $registration->ssd_code }} / {{ $registration->accommodation_status ?: '-' }}@endif</td></tr>
                <tr><td>Registration Period</td><td>{{ str_replace('_', ' ', $registration->registration_period_type ?: $registration->registration_period) }}</td></tr>
            </table>
        </div>

        <div class="card">
            <h2>Fee Summary</h2>
            <table class="summary-table">
                <tr><td>AP Exam + Service Fee</td><td>{{ $registration->currency }} {{ number_format($registration->exam_fee_total + $registration->service_fee_total) }}</td></tr>
                <tr><td>Practice Exam Fee</td><td>{{ $registration->currency }} {{ number_format($registration->practice_exam_total) }}</td></tr>
                <tr><td>Late Fee</td><td>{{ $registration->currency }} {{ number_format($registration->late_fee_total) }}</td></tr>
                <tr><td>Total Due</td><td class="amount">{{ $registration->currency }} {{ number_format($registration->grand_total ?: $registration->total_fee) }}</td></tr>
                <tr><td>Payment Method</td><td>{{ str_replace('_', ' ', $registration->payment_method ?: 'manual bank transfer') }}</td></tr>
            </table>
        </div>
    </section>

    <section class="card">
        <h2>Next Steps</h2>
        <ol class="steps">
            <li>Open the payment page and complete bank transfer or gateway payment.</li>
            <li>Upload proof of payment if using bank transfer.</li>
            <li>The admin team reviews passport, payment, and subject availability.</li>
            <li>Final registration confirmation is sent by email after payment and verification are complete.</li>
        </ol>
    </section>

    <section class="card">
        <h2>Submitted Signatures</h2>
        <table class="summary-table">
            <tr><td>Student</td><td>{{ $registration->student_signature_name ?: '-' }} / {{ optional($registration->student_signature_date)->format('Y-m-d') ?: '-' }}</td></tr>
            <tr><td>Parent / Guardian</td><td>{{ $registration->guardian_signature_name ?: '-' }} / {{ optional($registration->guardian_signature_date)->format('Y-m-d') ?: '-' }}</td></tr>
        </table>
    </section>
</x-public-flow-shell>
