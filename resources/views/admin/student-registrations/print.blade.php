<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print {{ $registration->registration_number }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <style>
        body{font-family:"Open Sans",Arial,sans-serif;color:#111}.wrap{max-width:820px;margin:0 auto}h1,h2{color:#153764;font-family:"Playfair Display",Georgia,serif}h2{font-size:16px;margin-top:24px}table{width:100%;border-collapse:collapse}td{padding:8px;border-bottom:1px solid #ddd;vertical-align:top}td:first-child{font-weight:bold;width:34%}.muted{color:#555;font-size:12px}@media print{button{display:none}}
    </style>
</head>
<body>
<div class="wrap">
    <button onclick="window.print()">Print</button>
    <h1>AP Registration {{ $registration->registration_number }}</h1>

    <h2>Student</h2>
    <table>
        <tr><td>Student</td><td>{{ $registration->student_full_name }}</td></tr>
        <tr><td>English Legal Name</td><td>{{ collect([$registration->family_name_en, $registration->first_name_en, $registration->middle_name])->filter()->implode(' ') ?: '-' }}</td></tr>
        <tr><td>Chinese Legal Name</td><td>{{ $registration->chinese_legal_name ?: '-' }}</td></tr>
        <tr><td>DOB / Nationality</td><td>{{ optional($registration->date_of_birth)->format('Y-m-d') ?: '-' }} / {{ $registration->nationality ?: '-' }}</td></tr>
        <tr><td>Passport</td><td>{{ $registration->passport_number }} @if($registration->passport_expiry_date)<span class="muted">expires {{ $registration->passport_expiry_date->format('Y-m-d') }}</span>@endif</td></tr>
        <tr><td>Email / Phone</td><td>{{ $registration->student_email }} / {{ $registration->student_phone ?: '-' }}</td></tr>
        <tr><td>School / Grade</td><td>{{ $registration->school_name }} / {{ $registration->grade_level }}</td></tr>
    </table>

    <h2>Guardian</h2>
    <table>
        <tr><td>Parent</td><td>{{ $registration->contact?->parent_full_name }} ({{ $registration->contact?->relationship ?: '-' }})</td></tr>
        <tr><td>Parent Contact</td><td>{{ $registration->contact?->parent_email }} / {{ $registration->contact?->parent_phone }}</td></tr>
        <tr><td>Mailing Address</td><td>{{ collect([$registration->contact?->mailing_address, $registration->contact?->mailing_city, $registration->contact?->postal_code])->filter()->implode(', ') ?: '-' }}</td></tr>
        <tr><td>Emergency</td><td>{{ $registration->contact?->emergency_contact_name }} / {{ $registration->contact?->emergency_contact_phone }} / {{ $registration->contact?->emergency_contact_relationship }}</td></tr>
    </table>

    <h2>Exams & Payment</h2>
    <table>
        <tr><td>Regular Exams</td><td>{{ $registration->exams->pluck('name')->join(', ') ?: '-' }}</td></tr>
        <tr><td>Practice Exams</td><td>{{ $registration->practiceExamSelections->pluck('exam_name')->join(', ') ?: '-' }}</td></tr>
        <tr><td>Accommodations</td><td>{{ $registration->needs_accommodations ? 'Yes' : 'No' }} @if($registration->ssd_code) / SSD {{ $registration->ssd_code }} @endif</td></tr>
        <tr><td>Status</td><td>{{ $registration->status }} / payment {{ $registration->payment_status }}</td></tr>
        <tr><td>Total</td><td>{{ $registration->currency }} {{ number_format($registration->grand_total ?: $registration->total_fee) }}</td></tr>
    </table>
</div>
</body>
</html>
