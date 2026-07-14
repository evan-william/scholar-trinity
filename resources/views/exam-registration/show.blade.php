<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Submitted | {{ $registration->reference_number }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary:#1a3a6b; --accent:#c9a84c; --muted:#667085; --line:#d5dae3; --soft:#f5f7fa; --success:#238654; }
        * { box-sizing: border-box; }
        body { margin:0; min-height:100vh; background:var(--soft); color:#253142; font-family:"Open Sans","Microsoft JhengHei",Arial,sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family:"Playfair Display",Georgia,"Microsoft JhengHei",serif; }
        .header { display:flex; align-items:center; gap:16px; padding:14px 24px; color:white; background:var(--primary); flex-wrap:wrap; }
        .logo-pill { min-width:74px; padding:7px 10px; border-radius:6px; color:var(--primary); background:white; font-size:10px; line-height:1.25; letter-spacing:.4px; text-align:center; font-weight:800; }
        .header-title h1 { margin:0; font-size:17px; }
        .header-title p { margin:3px 0 0; font-size:12px; opacity:.78; }
        .main { max-width:760px; margin:0 auto; padding:28px 16px; }
        .card { background:white; border-radius:8px; box-shadow:0 2px 16px rgba(18,34,57,.09); padding:28px 24px; margin-bottom:16px; }
        .success { text-align:center; padding:24px 10px; }
        .success-icon { width:64px; height:64px; display:grid; place-items:center; margin:0 auto 14px; border-radius:50%; background:#e8f6ef; color:var(--success); font-size:34px; font-weight:900; }
        h1, h2 { color:var(--primary); }
        h2 { margin:0 0 14px; padding-bottom:9px; border-bottom:2px solid var(--accent); font-size:17px; }
        p { color:var(--muted); line-height:1.65; }
        .ref { display:inline-block; margin:12px 0; padding:13px 20px; border-radius:8px; background:#f5f7fa; font-size:15px; }
        table { width:100%; border-collapse:collapse; }
        td { padding:8px 0; border-bottom:1px solid #edf0f5; vertical-align:top; font-size:13px; }
        td:first-child { width:38%; color:var(--muted); padding-right:12px; }
        .exam-tag { display:inline-block; margin:2px; padding:4px 9px; border-radius:999px; background:rgba(26,58,107,.09); color:var(--primary); font-size:11px; font-weight:700; }
        .btn { display:inline-block; margin-top:14px; padding:11px 18px; border-radius:6px; background:var(--primary); color:white; text-decoration:none; font-weight:800; font-size:14px; }
        .notice { padding:14px 16px; border:1px solid #efcf78; border-left:4px solid var(--accent); border-radius:8px; background:#fff8e1; color:#55410b; font-size:13px; line-height:1.6; }
    </style>
</head>
<body>
<header class="header">
    <div class="logo-pill">THE<br>PRIMACY<br>COLLEGIATE</div>
    <div class="logo-pill">TRINITY<br>SCHOLAR</div>
    <div class="header-title">
        <h1>AP Exam Registration</h1>
        <p>TPCA x Trinity Scholar | Submission Confirmation</p>
    </div>
</header>
<main class="main">
    <div class="card success">
        <div class="success-icon">✓</div>
        <h1>Registration Submitted</h1>
        <p>Your AP Exam registration has been received. The team will review the passport upload, exam selections, and payment method before confirming the registration.</p>
        <div class="ref">Reference No. <strong>{{ $registration->reference_number }}</strong></div>
        <p>Confirmation details should be sent to <strong>{{ $registration->student_email }}</strong> and <strong>{{ $registration->parent_email }}</strong>.</p>
        <a class="btn" href="{{ route('registrations.create') }}">Start Another Registration</a>
    </div>

    <div class="card">
        <h2>Registration Summary</h2>
        <table>
            <tr><td>Student</td><td>{{ $registration->student_family_name }} {{ $registration->student_first_name }} {{ $registration->student_middle_initial }}</td></tr>
            <tr><td>Chinese Name</td><td>{{ $registration->student_chinese_name }}</td></tr>
            <tr><td>School / Grade</td><td>{{ $registration->school }} / Grade {{ $registration->grade }}</td></tr>
            <tr><td>Parent</td><td>{{ $registration->parent_first_name }} {{ $registration->parent_last_name }} ({{ $registration->relationship }})</td></tr>
            <tr><td>Registration Round</td><td>{{ ucfirst($registration->registration_round) }}</td></tr>
            <tr><td>Payment Method</td><td>{{ str_replace('_', ' ', ucfirst($registration->payment_method)) }} | Status: {{ ucfirst($registration->payment_status) }}</td></tr>
            <tr>
                <td>Selected Exams</td>
                <td>
                    @foreach ($registration->selected_exams as $exam)
                        <span class="exam-tag">{{ $exam['name'] }}</span>
                    @endforeach
                    @foreach (($registration->other_exams ?? []) as $exam)
                        <span class="exam-tag">{{ $exam }}</span>
                    @endforeach
                </td>
            </tr>
            <tr><td>Exam Fees</td><td>NT$ {{ number_format($registration->exam_fee_total + $registration->practice_fee_total + $registration->late_fee_total) }}</td></tr>
            <tr><td>Service Fee Receipt / Fapiao</td><td>NT$ {{ number_format($registration->service_fee_total) }} only</td></tr>
            <tr><td>Total Due</td><td><strong>NT$ {{ number_format($registration->total_due) }}</strong></td></tr>
        </table>
    </div>

    <div class="notice">
        Next steps: verify registration details, complete payment using the selected method, then wait for the coordinator's final confirmation email. Passport files are stored privately and are not exposed from the public web directory.
    </div>
</main>
</body>
</html>
