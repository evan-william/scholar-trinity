<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('student_registration.successful') }} | {{ $registration->registration_number }}</title>
    <style>
        :root{--primary:#1a3a6b;--accent:#c9a84c;--success:#237a4f;--gray-50:#f8f9fa;--gray-200:#e9ecef;--gray-600:#6c757d;--gray-800:#343a40;--radius:8px;--shadow:0 2px 16px rgba(0,0,0,.09)}*{box-sizing:border-box}body{margin:0;background:var(--gray-50);color:var(--gray-800);font-family:-apple-system,BlinkMacSystemFont,"Segoe UI","Microsoft JhengHei","PingFang TC",Arial,sans-serif}.header{background:var(--primary);color:#fff;padding:16px 24px}.head{max-width:920px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap}.head h1{font-size:17px;margin:0}.head p{font-size:12px;margin:3px 0 0;opacity:.78}.badge{background:var(--accent);color:#4b3200;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:800}.wrap{max-width:900px;margin:0 auto;padding:28px 16px}.card{background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);padding:26px 24px;margin-bottom:16px}.confirm{text-align:center}.ok{width:66px;height:66px;border-radius:50%;display:grid;place-items:center;background:#e8f6ef;color:var(--success);font-size:20px;font-weight:900;margin:0 auto 14px}h1,h2{color:var(--primary)}h1{font-size:24px;margin:0 0 8px}h2{font-size:17px;border-bottom:2px solid var(--accent);padding-bottom:8px;margin:0 0 14px}.ref{display:inline-block;background:var(--gray-50);border:1px solid var(--gray-200);border-radius:8px;padding:13px 18px;margin:14px 0}.muted{color:var(--gray-600);font-size:12px;line-height:1.6}table{width:100%;border-collapse:collapse}td{padding:8px 0;border-bottom:1px solid #edf0f5;font-size:13px;vertical-align:top}td:first-child{color:var(--gray-600);width:38%;padding-right:12px}.grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}.steps{background:var(--gray-50);border-radius:8px;padding:14px 18px}.steps li{font-size:13px;margin-bottom:7px;line-height:1.5}.btn{display:inline-flex;background:var(--primary);color:#fff;text-decoration:none;padding:11px 16px;border-radius:6px;font-weight:900}.btn.light{background:#fff;color:var(--primary);border:1.5px solid var(--gray-200)}@media(max-width:720px){.grid{grid-template-columns:1fr}.card{padding:22px 16px}}
    </style>
</head>
<body>
<header class="header">
    <div class="head">
        <div><h1>AP Exam Registration / AP 考試報名</h1><p>Step 6 - Confirmation / 第 6 步 - 完成確認</p></div>
        <span class="badge">Submitted / 已送出</span>
    </div>
</header>
<main class="wrap">
    <div class="card confirm">
        <div class="ok">OK</div>
        <h1>Registration Submitted! / 報名已送出</h1>
        <p>Your AP Exam registration has been received. The AP Coordinator will review it and contact you to confirm payment.<br><br>您的 AP 考試報名已收到。AP 協調員將審核並聯繫您確認付款。</p>
        <div class="ref">Registration Reference No. / 報名參考編號<br><strong>{{ $registration->registration_number }}</strong></div>
        <p class="muted">Confirmation email sent to / 確認信已寄至 <strong>{{ $registration->student_email }}</strong>@if($registration->contact?->parent_email) and / 以及 <strong>{{ $registration->contact->parent_email }}</strong>@endif</p>
        <p><a class="btn" href="{{ route('payments.show', $registration->registration_number) }}">Continue to Payment / 前往付款</a> <a class="btn light" href="{{ route('landing') }}">Back to Landing / 回首頁</a></p>
    </div>

    <div class="grid">
        <div class="card">
            <h2>Student Information / 學生資料</h2>
            <table>
                <tr><td>Student / 學生</td><td>{{ $registration->student_full_name }}</td></tr>
                <tr><td>Legal Name / 法定姓名</td><td>{{ collect([$registration->family_name_en, $registration->first_name_en, $registration->middle_name])->filter()->implode(' ') ?: '-' }} @if($registration->chinese_legal_name)<br>{{ $registration->chinese_legal_name }}@endif</td></tr>
                <tr><td>DOB / Nationality</td><td>{{ optional($registration->date_of_birth)->format('Y-m-d') ?: '-' }} / {{ $registration->nationality ?: '-' }}</td></tr>
                <tr><td>Email / 電子郵件</td><td>{{ $registration->student_email }}</td></tr>
                <tr><td>Phone / 電話</td><td>{{ $registration->student_phone ?: '-' }}</td></tr>
                <tr><td>School / 學校</td><td>{{ $registration->school_name }}</td></tr>
                <tr><td>Grade / 年級</td><td>{{ $registration->grade_level }}</td></tr>
                <tr><td>Passport / 護照</td><td>{{ $registration->passport_number }}<br>{{ $registration->passport_upload_status === 'pending_review' ? 'Uploaded, pending review / 已上傳，待審核' : 'Pending coordinator review / 待協調員確認' }}</td></tr>
            </table>
        </div>
        <div class="card">
            <h2>Parent Information / 家長資料</h2>
            <table>
                <tr><td>Parent / 家長</td><td>{{ $registration->contact?->parent_full_name ?: '-' }}</td></tr>
                <tr><td>Relationship / 關係</td><td>{{ $registration->contact?->relationship ?: '-' }}</td></tr>
                <tr><td>Email / 電子郵件</td><td>{{ $registration->contact?->parent_email ?: '-' }}</td></tr>
                <tr><td>Phone / 電話</td><td>{{ $registration->contact?->parent_phone ?: '-' }}</td></tr>
                <tr><td>Mailing Address / 通訊地址</td><td>{{ collect([$registration->contact?->mailing_address, $registration->contact?->mailing_city, $registration->contact?->postal_code])->filter()->implode(', ') ?: '-' }}</td></tr>
                <tr><td>Emergency / 緊急聯絡人</td><td>{{ $registration->contact?->emergency_contact_name ?: '-' }} {{ $registration->contact?->emergency_contact_phone ? '/ '.$registration->contact->emergency_contact_phone : '' }} @if($registration->contact?->emergency_contact_relationship)<br>{{ $registration->contact->emergency_contact_relationship }}@endif</td></tr>
            </table>
        </div>
    </div>

    <div class="card">
        <h2>Selected Exams and Fee Summary / 已選考科與費用摘要</h2>
        <table>
            <tr><td>Selected Exams / 已選考科</td><td>{{ $registration->exams->pluck('name')->join(', ') ?: '-' }}</td></tr>
            <tr><td>Practice Exams / 模擬考</td><td>{{ $registration->practiceExamSelections->pluck('exam_name')->join(', ') ?: '-' }}</td></tr>
            <tr><td>Accommodations / 特殊需求</td><td>{{ $registration->needs_accommodations ? 'Yes' : 'No' }} @if($registration->ssd_code)<br>SSD {{ $registration->ssd_code }} / {{ $registration->accommodation_status ?: '-' }}@endif</td></tr>
            <tr><td>Regular Exam Fee / 正式考試費</td><td>{{ $registration->currency }} {{ number_format($registration->exam_fee_total + $registration->service_fee_total) }}</td></tr>
            <tr><td>Practice Exam Fee / 模擬考費</td><td>{{ $registration->currency }} {{ number_format($registration->practice_exam_total) }}</td></tr>
            <tr><td>Late Fee / 逾期費</td><td>{{ $registration->currency }} {{ number_format($registration->late_fee_total) }}</td></tr>
            <tr><td>Total Due / 應付總額</td><td><strong>{{ $registration->currency }} {{ number_format($registration->grand_total ?: $registration->total_fee) }}</strong></td></tr>
            <tr><td>Payment Method / 付款方式</td><td>{{ str_replace('_', ' ', $registration->payment_method ?: 'manual_bank_transfer') }}</td></tr>
            <tr><td>Status / 狀態</td><td>{{ str_replace('_', ' ', $registration->status) }}</td></tr>
        </table>
    </div>

    <div class="card">
        <h2>Next Steps / 後續步驟</h2>
        <div class="steps">
            <ol>
                <li>AP Coordinator verifies your registration. / AP 協調員審核您的報名資料。</li>
                <li>Complete payment by the registration deadline. / 請於截止日前完成付款。</li>
                <li>Confirm payment with the school cashier. / 請向學校出納確認付款。</li>
                <li>Watch your email for exam schedule details. / 請留意電子郵件中的考試時程通知。</li>
            </ol>
        </div>
    </div>
</main>
</body>
</html>
