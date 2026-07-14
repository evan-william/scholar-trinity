<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AP Exam Registration | TPCA x Trinity Scholar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1a3a6b;
            --primary-light: #2a5298;
            --accent: #c9a84c;
            --success: #238654;
            --danger: #c93d32;
            --ink: #253142;
            --muted: #667085;
            --line: #d5dae3;
            --soft: #f5f7fa;
            --white: #fff;
            --radius: 8px;
            --shadow: 0 2px 16px rgba(18, 34, 57, .09);
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            color: var(--ink);
            background: var(--soft);
            font-family: "Open Sans", "Microsoft JhengHei", Arial, sans-serif;
        }
        h1, h2, h3, h4, h5, h6 { font-family: "Playfair Display", Georgia, "Microsoft JhengHei", serif; }

        .header {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 14px 24px;
            color: white;
            background: var(--primary);
            flex-wrap: wrap;
        }

        .header-logos { display: flex; align-items: center; gap: 12px; }
        .logo-pill {
            min-width: 74px;
            padding: 7px 10px;
            border-radius: 6px;
            color: var(--primary);
            background: white;
            font-size: 10px;
            line-height: 1.25;
            letter-spacing: .4px;
            text-align: center;
            font-weight: 800;
        }
        .logo-divider { width: 1px; height: 38px; background: rgba(255,255,255,.3); }
        .header-title { flex: 1; min-width: 230px; }
        .header-title h1 { margin: 0; font-size: 17px; line-height: 1.35; }
        .header-title p { margin: 3px 0 0; font-size: 12px; opacity: .78; }
        .header-badge {
            padding: 6px 14px;
            border-radius: 999px;
            color: #503500;
            background: var(--accent);
            font-weight: 700;
            font-size: 12px;
            white-space: nowrap;
        }

        .progress-wrap {
            overflow-x: auto;
            padding: 0 16px;
            background: white;
            border-bottom: 1px solid #e7eaf0;
        }
        .progress-steps {
            display: flex;
            max-width: 860px;
            min-width: max-content;
            margin: 0 auto;
        }
        .step-item {
            position: relative;
            flex: 1;
            min-width: 130px;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 14px 8px;
        }
        .step-item:not(:last-child)::after {
            content: "";
            position: absolute;
            top: 28px;
            left: calc(50% + 18px);
            right: calc(-50% + 18px);
            height: 2px;
            background: #e7eaf0;
        }
        .step-item.completed:not(:last-child)::after { background: var(--primary); }
        .step-circle {
            position: relative;
            z-index: 1;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 2px solid #cbd3df;
            background: white;
            color: var(--muted);
            display: grid;
            place-items: center;
            font-size: 12px;
            font-weight: 800;
        }
        .step-item.active .step-circle,
        .step-item.completed .step-circle { color: white; background: var(--primary); border-color: var(--primary); }
        .step-label {
            margin-top: 6px;
            color: var(--muted);
            text-align: center;
            font-size: 10.5px;
            line-height: 1.35;
        }
        .step-item.active .step-label { color: var(--primary); font-weight: 700; }

        .main {
            max-width: 760px;
            margin: 0 auto;
            padding: 24px 16px 98px;
        }
        .card {
            margin-bottom: 16px;
            padding: 26px 24px;
            border-radius: var(--radius);
            background: white;
            box-shadow: var(--shadow);
        }
        .section-title {
            margin: 0 0 20px;
            padding-bottom: 9px;
            border-bottom: 2px solid var(--accent);
            color: var(--primary);
            font-size: 17px;
            font-weight: 800;
        }
        .section-title span {
            display: block;
            margin-top: 2px;
            color: var(--muted);
            font-size: 12px;
            font-weight: 400;
        }

        .row { display: grid; gap: 14px; margin-bottom: 14px; }
        .row-1 { grid-template-columns: 1fr; }
        .row-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .row-3 { grid-template-columns: 1fr 1fr .55fr; }
        @media (max-width: 560px) {
            .row-2, .row-3 { grid-template-columns: 1fr; }
            .card { padding: 22px 18px; }
            .header { padding: 14px 16px; }
        }

        .fg { display: flex; flex-direction: column; gap: 6px; }
        .span2 { grid-column: 1 / -1; }
        label.lbl { color: var(--ink); font-size: 13px; font-weight: 700; }
        .zh { display: block; color: var(--muted); font-size: 11px; font-weight: 400; }
        .req { margin-left: 2px; color: var(--danger); }

        input[type=text], input[type=email], input[type=tel], select, textarea {
            width: 100%;
            min-height: 40px;
            border: 1.5px solid #cbd3df;
            border-radius: 6px;
            padding: 9px 12px;
            background: white;
            color: var(--ink);
            font: inherit;
            font-size: 14px;
        }
        textarea { min-height: 88px; resize: vertical; }
        select {
            appearance: none;
            padding-right: 34px;
            background-image: linear-gradient(45deg, transparent 50%, #667085 50%), linear-gradient(135deg, #667085 50%, transparent 50%);
            background-position: calc(100% - 17px) 17px, calc(100% - 11px) 17px;
            background-size: 6px 6px, 6px 6px;
            background-repeat: no-repeat;
        }
        input:focus, select:focus, textarea:focus {
            outline: 0;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26, 58, 107, .1);
        }
        .hint { margin: 3px 0 0; color: var(--muted); font-size: 11px; line-height: 1.45; }

        .notice {
            margin-bottom: 18px;
            padding: 14px 16px;
            border: 1px solid #efcf78;
            border-left: 4px solid var(--accent);
            border-radius: var(--radius);
            background: #fff8e1;
        }
        .notice h4 { margin: 0 0 7px; color: #6d5000; font-size: 13px; }
        .notice p, .notice li { color: #55410b; font-size: 12px; line-height: 1.65; }
        .notice ul { margin: 0; padding-left: 17px; }

        .error-box {
            margin-bottom: 16px;
            padding: 14px 16px;
            border-radius: var(--radius);
            background: #fff1f0;
            border: 1px solid #ffc9c4;
            color: #86231b;
            font-size: 13px;
        }
        .field-error { color: var(--danger); font-size: 11px; }

        .upload-area {
            position: relative;
            padding: 24px 16px;
            border: 2px dashed #b7c0ce;
            border-radius: var(--radius);
            text-align: center;
            background: #f8fafc;
        }
        .upload-area:hover { border-color: var(--primary); background: rgba(26,58,107,.03); }
        .upload-area input[type=file] { position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
        .upload-icon { font-size: 30px; color: var(--primary); margin-bottom: 6px; }
        .upload-text { color: var(--muted); font-size: 13px; }
        .upload-text strong { color: var(--primary); }
        .upload-selected { display: none; margin-top: 8px; color: var(--success); font-size: 13px; font-weight: 700; }

        .choice-grid, .exam-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px; }
        @media (max-width: 520px) { .choice-grid, .exam-grid { grid-template-columns: 1fr; } }
        .choice, .exam-cb, .pay-opt {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            padding: 11px 12px;
            border: 1.5px solid #dce1e9;
            border-radius: 6px;
            background: white;
            cursor: pointer;
        }
        .choice input, .exam-cb input, .pay-opt input, .check-line input {
            width: 16px;
            height: 16px;
            margin-top: 2px;
            accent-color: var(--primary);
        }
        .choice.checked, .exam-cb.checked, .pay-opt.selected { border-color: var(--primary); background: rgba(26,58,107,.05); }
        .choice strong, .pay-opt strong { display: block; font-size: 13px; }
        .choice span, .pay-opt span { display: block; color: var(--muted); font-size: 11px; line-height: 1.45; }

        .exam-sticky {
            position: sticky;
            top: 0;
            z-index: 2;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            padding: 10px 0;
            border-bottom: 1px solid #e7eaf0;
            background: white;
        }
        .sel-badge {
            display: inline-flex;
            align-items: center;
            padding: 5px 13px;
            border-radius: 999px;
            background: var(--primary);
            color: white;
            font-size: 12px;
            font-weight: 800;
        }
        .price-preview { color: var(--primary); font-size: 14px; font-weight: 800; }
        .cat-title {
            margin: 14px 0 10px;
            padding: 6px 12px;
            border-radius: 4px;
            color: var(--primary);
            background: #f0f3f8;
            font-size: 12px;
            font-weight: 800;
        }
        .exam-cb { min-height: 58px; }
        .exam-name { font-size: 12px; font-weight: 700; line-height: 1.35; }
        .exam-sub { color: var(--muted); font-size: 10px; margin-top: 2px; }
        .exam-price-tag { margin-left: auto; white-space: nowrap; color: var(--primary); font-size: 11px; font-weight: 800; }

        .price-box {
            margin-top: 12px;
            padding: 14px 16px;
            border: 1px solid #e1e5ed;
            border-radius: var(--radius);
            background: #f8fafc;
        }
        .price-row { display: flex; justify-content: space-between; gap: 14px; padding: 5px 0; font-size: 13px; }
        .price-row.total {
            margin-top: 8px;
            padding-top: 10px;
            border-top: 2px solid var(--primary);
            color: var(--primary);
            font-size: 15px;
            font-weight: 800;
        }

        .check-line { display: flex; align-items: flex-start; gap: 9px; font-size: 13px; line-height: 1.5; cursor: pointer; }
        .review-table { width: 100%; border-collapse: collapse; }
        .review-table td { padding: 6px 0; vertical-align: top; font-size: 12.5px; }
        .review-table td:first-child { width: 38%; padding-right: 12px; color: var(--muted); }
        .exam-tag {
            display: inline-block;
            margin: 2px;
            padding: 4px 9px;
            border-radius: 999px;
            background: rgba(26,58,107,.09);
            color: var(--primary);
            font-size: 11px;
            font-weight: 700;
        }
        hr.div { margin: 15px 0; border: 0; border-top: 1px solid #e7eaf0; }

        .nav-footer {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 5;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 14px 24px;
            border-top: 1px solid #e7eaf0;
            background: white;
        }
        .btn {
            min-height: 42px;
            border: 0;
            border-radius: 6px;
            padding: 10px 24px;
            font: inherit;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
        }
        .btn-primary { background: var(--primary); color: white; }
        .btn-success { background: var(--success); color: white; }
        .btn-outline { border: 2px solid #cbd3df; color: var(--ink); background: white; }
        .step-ind { color: var(--muted); font-size: 12px; }
        .hidden { display: none !important; }
    </style>
</head>
<body>
<header class="header">
    <div class="header-logos">
        <div class="logo-pill">THE<br>PRIMACY<br>COLLEGIATE</div>
        <div class="logo-divider"></div>
        <div class="logo-pill">TRINITY<br>SCHOLAR</div>
    </div>
    <div class="header-title">
        <h1>AP Exam Registration | Outside Students</h1>
        <p>TPCA x Trinity Scholar | 2026-2027 Academic Year</p>
    </div>
    <div class="header-badge">Secure Intake + Payment Review</div>
</header>

<div class="progress-wrap">
    <div class="progress-steps">
        <div class="step-item active" data-step-item="1"><div class="step-circle">1</div><div class="step-label">Student Info<br>學生資料</div></div>
        <div class="step-item" data-step-item="2"><div class="step-circle">2</div><div class="step-label">Parent Info<br>家長資料</div></div>
        <div class="step-item" data-step-item="3"><div class="step-circle">3</div><div class="step-label">Exam Selection<br>考試選擇</div></div>
        <div class="step-item" data-step-item="4"><div class="step-circle">4</div><div class="step-label">Accommodations<br>特殊安排</div></div>
        <div class="step-item" data-step-item="5"><div class="step-circle">5</div><div class="step-label">Review & Pay<br>確認付款</div></div>
    </div>
</div>

<main class="main">
    @if ($errors->any())
        <div class="error-box">
            <strong>Please check the highlighted fields.</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="registrationForm" action="{{ route('registrations.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <section id="step1" data-step="1">
            <div class="card">
                <h2 class="section-title">Student Information <span>學生基本資料</span></h2>
                <div class="row row-3">
                    <div class="fg">
                        <label class="lbl">Family Name <span class="req">*</span><span class="zh">英文姓氏，需與護照一致</span></label>
                        <input name="student_family_name" type="text" value="{{ old('student_family_name') }}" required placeholder="CHEN">
                        @error('student_family_name')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="fg">
                        <label class="lbl">First Name <span class="req">*</span><span class="zh">英文名字，需與護照一致</span></label>
                        <input name="student_first_name" type="text" value="{{ old('student_first_name') }}" required placeholder="MING-HUA">
                        @error('student_first_name')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="fg">
                        <label class="lbl">M.I. <span class="zh">Middle initial</span></label>
                        <input name="student_middle_initial" type="text" value="{{ old('student_middle_initial') }}" maxlength="5" placeholder="A">
                    </div>
                </div>
                <div class="row row-2">
                    <div class="fg">
                        <label class="lbl">Middle Name <span class="zh">Optional</span></label>
                        <input name="student_middle_name" type="text" value="{{ old('student_middle_name') }}" placeholder="ALEX">
                    </div>
                    <div class="fg">
                        <label class="lbl">Chinese Legal Name <span class="req">*</span><span class="zh">中文法定姓名</span></label>
                        <input name="student_chinese_name" type="text" value="{{ old('student_chinese_name') }}" required placeholder="陳明華">
                        @error('student_chinese_name')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="row row-1">
                    <div class="fg">
                        <label class="lbl">English Name Used in Class <span class="zh">Class roster name</span></label>
                        <input name="student_class_name" type="text" value="{{ old('student_class_name') }}" placeholder="Alex Chen">
                    </div>
                </div>
                <div class="row row-2">
                    <div class="fg">
                        <label class="lbl">Grade <span class="req">*</span><span class="zh">年級</span></label>
                        <select name="grade" required>
                            <option value="">Select grade</option>
                            @foreach (['9' => '9th Grade', '10' => '10th Grade', '11' => '11th Grade', '12' => '12th Grade', 'other' => 'Other'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('grade') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('grade')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="fg">
                        <label class="lbl">Current School <span class="req">*</span><span class="zh">目前就讀學校</span></label>
                        <input name="school" type="text" value="{{ old('school') }}" required placeholder="Taipei American School">
                        @error('school')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="row row-2">
                    <div class="fg">
                        <label class="lbl">Student Email <span class="req">*</span><span class="zh">學生電子郵件</span></label>
                        <input name="student_email" type="email" value="{{ old('student_email') }}" required placeholder="student@example.com">
                        @error('student_email')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="fg">
                        <label class="lbl">Student Phone <span class="req">*</span><span class="zh">學生聯絡電話</span></label>
                        <input name="student_phone" type="tel" value="{{ old('student_phone') }}" required placeholder="0912345678">
                        @error('student_phone')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="row row-1">
                    <div class="fg">
                        <label class="lbl">Passport Upload <span class="req">*</span><span class="zh">護照照片頁或 PDF，上限 10MB</span></label>
                        <div class="upload-area" id="dropZone">
                            <input name="passport" id="passportInput" type="file" accept=".pdf,.jpg,.jpeg,.png" required>
                            <div class="upload-icon">ID</div>
                            <div class="upload-text"><strong>Click to upload</strong> or drag and drop</div>
                            <p class="hint">Stored on Laravel's private local disk, not public web storage.</p>
                            <div class="upload-selected" id="fileLabel"></div>
                        </div>
                        @error('passport')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <div class="notice">
                <h4>Important Notice / 重要提醒</h4>
                <ul>
                    <li>Registration runs August to October. Late registration runs January to March and adds a late fee.</li>
                    <li>Except AP Chinese, late or exception sessions may not be available. Please check schedule conflicts before submitting.</li>
                    <li>Passport information must match the College Board registration record.</li>
                </ul>
            </div>
        </section>

        <section id="step2" data-step="2" class="hidden">
            <div class="card">
                <h2 class="section-title">Parent / Guardian Information <span>家長或監護人資料</span></h2>
                <div class="row row-2">
                    <div class="fg">
                        <label class="lbl">Parent First Name <span class="req">*</span></label>
                        <input name="parent_first_name" type="text" value="{{ old('parent_first_name') }}" required>
                        @error('parent_first_name')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="fg">
                        <label class="lbl">Parent Last Name <span class="req">*</span></label>
                        <input name="parent_last_name" type="text" value="{{ old('parent_last_name') }}" required>
                        @error('parent_last_name')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="row row-2">
                    <div class="fg">
                        <label class="lbl">Parent Email <span class="req">*</span></label>
                        <input name="parent_email" type="email" value="{{ old('parent_email') }}" required>
                        @error('parent_email')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="fg">
                        <label class="lbl">Parent Phone <span class="req">*</span></label>
                        <input name="parent_phone" type="tel" value="{{ old('parent_phone') }}" required>
                        @error('parent_phone')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="row row-2">
                    <div class="fg">
                        <label class="lbl">Relationship <span class="req">*</span></label>
                        <select name="relationship" required>
                            <option value="">Select</option>
                            @foreach (['Mother', 'Father', 'Guardian', 'Other'] as $relationship)
                                <option value="{{ $relationship }}" @selected(old('relationship') === $relationship)>{{ $relationship }}</option>
                            @endforeach
                        </select>
                        @error('relationship')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="fg">
                        <label class="lbl">Country <span class="req">*</span></label>
                        <input name="country" type="text" value="{{ old('country', 'Taiwan') }}" required>
                    </div>
                </div>
                <div class="row row-1">
                    <div class="fg">
                        <label class="lbl">Mailing Address <span class="req">*</span><span class="zh">收據與聯絡地址</span></label>
                        <input name="address_line_1" type="text" value="{{ old('address_line_1') }}" required placeholder="Street address">
                        @error('address_line_1')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="row row-2">
                    <div class="fg"><input name="address_line_2" type="text" value="{{ old('address_line_2') }}" placeholder="Apartment, suite, district"></div>
                    <div class="fg"><input name="city" type="text" value="{{ old('city') }}" required placeholder="City"></div>
                </div>
                <div class="row row-1">
                    <div class="fg"><input name="postal_code" type="text" value="{{ old('postal_code') }}" placeholder="Postal code"></div>
                </div>
            </div>
        </section>

        <section id="step3" data-step="3" class="hidden">
            <div class="card">
                <h2 class="section-title">Registration Round <span>報名梯次</span></h2>
                <div class="choice-grid">
                    <label class="choice">
                        <input type="radio" name="registration_round" value="regular" data-late="0" @checked(old('registration_round', 'regular') === 'regular') required>
                        <span><strong>Regular: August - October</strong><span>Exam fee + Trinity service fee.</span></span>
                    </label>
                    <label class="choice">
                        <input type="radio" name="registration_round" value="late" data-late="{{ $lateRegistrationFee }}" @checked(old('registration_round') === 'late')>
                        <span><strong>Late: January - March</strong><span>Adds NT${{ number_format($lateRegistrationFee) }} late registration fee.</span></span>
                    </label>
                </div>
            </div>

            <div class="card">
                <h2 class="section-title">Exam Selection <span>AP 正式考試與模擬考選擇</span></h2>
                <div class="exam-sticky">
                    <span class="sel-badge" id="selectedBadge">0 selected</span>
                    <span class="price-preview" id="pricePreview">NT$ 0</span>
                </div>

                @foreach (collect($regularExams)->groupBy('category') as $category => $exams)
                    <div class="cat-title">{{ $category }} | Regular AP Exam</div>
                    <div class="exam-grid">
                        @foreach ($exams as $exam)
                            <label class="exam-cb">
                                <input type="checkbox" name="selected_exams[]" value="{{ $exam['name'] }}" data-type="regular" data-fee="{{ $regularExamFee }}" @checked(in_array($exam['name'], old('selected_exams', []), true))>
                                <span><span class="exam-name">{{ $exam['name'] }}</span><span class="exam-sub">Official AP Exam</span></span>
                                <span class="exam-price-tag">NT${{ number_format($regularExamFee) }}</span>
                            </label>
                        @endforeach
                    </div>
                @endforeach

                <div class="notice">
                    <h4>Practice Exam Info</h4>
                    <p>Practice exams are optional and priced separately at NT${{ number_format($practiceExamFee) }} per exam.</p>
                </div>

                <div class="exam-grid">
                    @foreach ($practiceExams as $exam)
                        <label class="exam-cb">
                            <input type="checkbox" name="selected_exams[]" value="{{ $exam['name'] }}" data-type="practice" data-fee="{{ $practiceExamFee }}" @checked(in_array($exam['name'], old('selected_exams', []), true))>
                            <span><span class="exam-name">{{ str_replace('Practice: ', '', $exam['name']) }}</span><span class="exam-sub">Practice Exam</span></span>
                            <span class="exam-price-tag">NT${{ number_format($practiceExamFee) }}</span>
                        </label>
                    @endforeach
                </div>

                <div class="row row-2" style="margin-top: 16px;">
                    <div class="fg">
                        <label class="lbl">Other AP Exam Request</label>
                        <input name="other_exams[]" type="text" value="{{ old('other_exams.0') }}" placeholder="Subject not listed">
                    </div>
                    <div class="fg">
                        <label class="lbl">Second Other Request</label>
                        <input name="other_exams[]" type="text" value="{{ old('other_exams.1') }}" placeholder="Subject not listed">
                    </div>
                </div>

                @error('selected_exams')<span class="field-error">{{ $message }}</span>@enderror

                <div class="price-box">
                    <div class="price-row"><span>Regular AP exams (<span id="regularCount">0</span>)</span><span>NT$ <span id="regularTotal">0</span></span></div>
                    <div class="price-row"><span>Practice exams (<span id="practiceCount">0</span>)</span><span>NT$ <span id="practiceTotal">0</span></span></div>
                    <div class="price-row"><span>Late registration fee</span><span>NT$ <span id="lateTotal">0</span></span></div>
                    <div class="price-row"><span>Trinity service fee <strong>(fapiao applies here only)</strong></span><span>NT$ <span id="serviceTotal">{{ number_format($serviceFee) }}</span></span></div>
                    <div class="price-row total"><span>Total due</span><span>NT$ <span id="grandTotal">0</span></span></div>
                    <p class="hint">Exam fees and service fees are separated so receipts/fapiao can be issued only for the service fee.</p>
                </div>
            </div>
        </section>

        <section id="step4" data-step="4" class="hidden">
            <div class="card">
                <h2 class="section-title">Testing Accommodations <span>特殊考試安排</span></h2>
                <div class="notice">
                    <h4>About Accommodations</h4>
                    <p>If the student needs College Board approved accommodations, contact the AP Coordinator first. Add the SSD code and requested arrangements below so the team can verify the record.</p>
                </div>
                <label class="check-line" style="margin-bottom: 18px;">
                    <input type="checkbox" name="needs_accommodations" value="1" id="needsAccommodations" @checked(old('needs_accommodations'))>
                    <span>I am requesting testing accommodations.</span>
                </label>
                <div id="accommodationFields" class="hidden">
                    <div class="row row-2">
                        <div class="fg">
                            <label class="lbl">College Board SSD Code <span class="req">*</span></label>
                            <input name="ssd_code" type="text" value="{{ old('ssd_code') }}" placeholder="SSD Code">
                            @error('ssd_code')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="fg">
                            <label class="lbl">Approval Status</label>
                            <select name="accommodation_status">
                                <option value="">Select</option>
                                @foreach (['Already Approved', 'Pending', 'New Request'] as $status)
                                    <option value="{{ $status }}" @selected(old('accommodation_status') === $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="accommodationRows">
                        @for ($i = 0; $i < max(2, count(old('accommodation_exam', []))); $i++)
                            <div class="row row-2 accommodation-row">
                                <div class="fg"><input name="accommodation_exam[]" type="text" value="{{ old('accommodation_exam.'.$i) }}" placeholder="Exam name"></div>
                                <div class="fg"><input name="accommodation_detail[]" type="text" value="{{ old('accommodation_detail.'.$i) }}" placeholder="Accommodation requested"></div>
                            </div>
                        @endfor
                    </div>
                    <button type="button" class="btn btn-outline" id="addAccommodation" style="min-height: 36px; padding: 7px 14px;">Add row</button>
                </div>
            </div>
        </section>

        <section id="step5" data-step="5" class="hidden">
            <div class="card">
                <h2 class="section-title">Review Your Registration <span>送出前確認</span></h2>
                <table class="review-table">
                    <tr><td>Student</td><td id="reviewStudent">-</td></tr>
                    <tr><td>School / Grade</td><td id="reviewSchool">-</td></tr>
                    <tr><td>Contact</td><td id="reviewContact">-</td></tr>
                    <tr><td>Parent</td><td id="reviewParent">-</td></tr>
                    <tr><td>Passport</td><td id="reviewPassport">-</td></tr>
                    <tr><td>Selected Exams</td><td id="reviewExams">-</td></tr>
                    <tr><td>Total Due</td><td id="reviewTotal">-</td></tr>
                </table>
            </div>

            <div class="card">
                <h2 class="section-title">Payment Method <span>付款方式</span></h2>
                <div class="choice-grid">
                    <label class="pay-opt">
                        <input type="radio" name="payment_method" value="bank_transfer" @checked(old('payment_method', 'bank_transfer') === 'bank_transfer') required>
                        <span><strong>Bank Transfer</strong><span>Recommended for Taiwan families. Team confirms payment manually.</span></span>
                    </label>
                    <label class="pay-opt">
                        <input type="radio" name="payment_method" value="local_gateway" @checked(old('payment_method') === 'local_gateway')>
                        <span><strong>Taiwan Local Gateway</strong><span>Prepared for providers such as ECPay/NewebPay integration.</span></span>
                    </label>
                    <label class="pay-opt">
                        <input type="radio" name="payment_method" value="cash" @checked(old('payment_method') === 'cash')>
                        <span><strong>Cash</strong><span>Pay directly to school cashier after registration review.</span></span>
                    </label>
                    <label class="pay-opt">
                        <input type="radio" name="payment_method" value="card_pending" @checked(old('payment_method') === 'card_pending')>
                        <span><strong>Credit Card Portal</strong><span>Held for later if foreign card fees are acceptable.</span></span>
                    </label>
                </div>
            </div>

            <div class="card">
                <h2 class="section-title">Receipt / Fapiao <span>只針對服務費開立</span></h2>
                <div class="notice">
                    <h4>Receipt Scope</h4>
                    <p>Fapiao is issued for the Trinity service fee only. AP exam fees are collected separately on behalf of the exam provider and are excluded from the service-fee receipt.</p>
                </div>
                <div class="row row-2">
                    <div class="fg">
                        <label class="lbl">Receipt Type <span class="req">*</span></label>
                        <select name="receipt_type" required>
                            <option value="none" @selected(old('receipt_type') === 'none')>No receipt needed</option>
                            <option value="personal" @selected(old('receipt_type', 'personal') === 'personal')>Personal receipt</option>
                            <option value="business" @selected(old('receipt_type') === 'business')>Business fapiao</option>
                        </select>
                    </div>
                    <div class="fg">
                        <label class="lbl">Receipt Email</label>
                        <input name="receipt_email" type="email" value="{{ old('receipt_email') }}" placeholder="Defaults to parent email">
                    </div>
                </div>
                <div class="row row-2">
                    <div class="fg">
                        <label class="lbl">Business Title</label>
                        <input name="receipt_title" type="text" value="{{ old('receipt_title') }}" placeholder="Required for business fapiao">
                    </div>
                    <div class="fg">
                        <label class="lbl">Tax ID</label>
                        <input name="receipt_tax_id" type="text" value="{{ old('receipt_tax_id') }}" placeholder="Required for business fapiao">
                    </div>
                </div>
                <label class="check-line">
                    <input type="checkbox" name="terms" value="1" required @checked(old('terms'))>
                    <span>I confirm the registration is accurate, I have checked exam schedule conflicts, and I understand payment is non-refundable once confirmed. <span class="req">*</span></span>
                </label>
            </div>
        </section>
    </form>
</main>

<div class="nav-footer">
    <button class="btn btn-outline" id="backButton" type="button" style="visibility: hidden;">Back</button>
    <span class="step-ind" id="stepIndicator">Step 1 of 5</span>
    <button class="btn btn-primary" id="nextButton" type="button">Next</button>
</div>

<script>
    const fees = {
        regularExam: {{ $regularExamFee }},
        practiceExam: {{ $practiceExamFee }},
        service: {{ $serviceFee }},
        late: {{ $lateRegistrationFee }}
    };
    let currentStep = 1;
    const totalSteps = 5;

    const form = document.getElementById('registrationForm');
    const nextButton = document.getElementById('nextButton');
    const backButton = document.getElementById('backButton');
    const stepIndicator = document.getElementById('stepIndicator');

    function visibleFieldsAreValid(step) {
        const section = document.querySelector(`[data-step="${step}"]`);
        const requiredFields = [...section.querySelectorAll('[required]')];
        for (const field of requiredFields) {
            if (!field.checkValidity()) {
                field.reportValidity();
                return false;
            }
        }
        if (step === 3 && document.querySelectorAll('.exam-cb input:checked').length === 0) {
            alert('Please select at least one exam.');
            return false;
        }
        return true;
    }

    function setStep(step) {
        document.querySelectorAll('[data-step]').forEach(section => section.classList.add('hidden'));
        document.querySelector(`[data-step="${step}"]`).classList.remove('hidden');
        currentStep = step;

        document.querySelectorAll('.step-item').forEach(item => {
            const itemStep = Number(item.dataset.stepItem);
            item.classList.toggle('active', itemStep === step);
            item.classList.toggle('completed', itemStep < step);
            item.querySelector('.step-circle').textContent = itemStep < step ? '✓' : itemStep;
        });

        backButton.style.visibility = step === 1 ? 'hidden' : 'visible';
        stepIndicator.textContent = `Step ${step} of ${totalSteps}`;
        nextButton.textContent = step === totalSteps ? 'Submit Registration' : 'Next';
        nextButton.className = step === totalSteps ? 'btn btn-success' : 'btn btn-primary';

        if (step === totalSteps) {
            buildReview();
        }
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    nextButton.addEventListener('click', () => {
        if (!visibleFieldsAreValid(currentStep)) return;
        if (currentStep === totalSteps) {
            form.requestSubmit();
            return;
        }
        setStep(currentStep + 1);
    });
    backButton.addEventListener('click', () => setStep(Math.max(1, currentStep - 1)));

    function money(value) {
        return Number(value).toLocaleString('en-US');
    }

    function calculateFees() {
        let regularCount = 0;
        let practiceCount = 0;
        document.querySelectorAll('.exam-cb input').forEach(input => {
            input.closest('.exam-cb').classList.toggle('checked', input.checked);
            if (!input.checked) return;
            if (input.dataset.type === 'practice') practiceCount += 1;
            else regularCount += 1;
        });

        const lateFee = document.querySelector('input[name="registration_round"]:checked')?.value === 'late' ? fees.late : 0;
        const regularTotal = regularCount * fees.regularExam;
        const practiceTotal = practiceCount * fees.practiceExam;
        const grandTotal = regularTotal + practiceTotal + lateFee + fees.service;
        const selectedTotal = regularCount + practiceCount;

        document.getElementById('regularCount').textContent = regularCount;
        document.getElementById('practiceCount').textContent = practiceCount;
        document.getElementById('regularTotal').textContent = money(regularTotal);
        document.getElementById('practiceTotal').textContent = money(practiceTotal);
        document.getElementById('lateTotal').textContent = money(lateFee);
        document.getElementById('grandTotal').textContent = money(grandTotal);
        document.getElementById('selectedBadge').textContent = `${selectedTotal} selected`;
        document.getElementById('pricePreview').textContent = `NT$ ${money(grandTotal)}`;
        return { regularCount, practiceCount, regularTotal, practiceTotal, lateFee, grandTotal };
    }

    document.querySelectorAll('.exam-cb input, input[name="registration_round"]').forEach(input => {
        input.addEventListener('change', calculateFees);
    });
    document.querySelectorAll('.choice input').forEach(input => {
        input.addEventListener('change', () => {
            document.querySelectorAll('.choice').forEach(label => label.classList.remove('checked'));
            input.closest('.choice').classList.add('checked');
        });
    });
    document.querySelectorAll('.pay-opt input').forEach(input => {
        input.addEventListener('change', () => {
            document.querySelectorAll('.pay-opt').forEach(label => label.classList.remove('selected'));
            input.closest('.pay-opt').classList.add('selected');
        });
    });

    const passportInput = document.getElementById('passportInput');
    passportInput.addEventListener('change', () => {
        const label = document.getElementById('fileLabel');
        if (!passportInput.files.length) return;
        label.textContent = passportInput.files[0].name;
        label.style.display = 'block';
    });

    const needsAccommodations = document.getElementById('needsAccommodations');
    function toggleAccommodationFields() {
        document.getElementById('accommodationFields').classList.toggle('hidden', !needsAccommodations.checked);
    }
    needsAccommodations.addEventListener('change', toggleAccommodationFields);
    document.getElementById('addAccommodation').addEventListener('click', () => {
        const row = document.createElement('div');
        row.className = 'row row-2 accommodation-row';
        row.innerHTML = '<div class="fg"><input name="accommodation_exam[]" type="text" placeholder="Exam name"></div><div class="fg"><input name="accommodation_detail[]" type="text" placeholder="Accommodation requested"></div>';
        document.getElementById('accommodationRows').appendChild(row);
    });

    function field(name) {
        return form.elements[name]?.value || '';
    }

    function buildReview() {
        const totals = calculateFees();
        const selected = [...document.querySelectorAll('.exam-cb input:checked')].map(input => input.value);
        const other = [...document.querySelectorAll('input[name="other_exams[]"]')].map(input => input.value).filter(Boolean);
        const examHtml = [...selected, ...other].length
            ? [...selected, ...other].map(name => `<span class="exam-tag">${name}</span>`).join('')
            : '-';

        document.getElementById('reviewStudent').textContent = [field('student_family_name'), field('student_first_name'), field('student_middle_initial')].filter(Boolean).join(' ');
        document.getElementById('reviewSchool').textContent = `${field('school')} / Grade ${field('grade')}`;
        document.getElementById('reviewContact').textContent = `${field('student_email')} / ${field('student_phone')}`;
        document.getElementById('reviewParent').textContent = `${field('parent_first_name')} ${field('parent_last_name')} / ${field('parent_email')}`;
        document.getElementById('reviewPassport').textContent = passportInput.files[0]?.name || 'Uploaded file will be attached';
        document.getElementById('reviewExams').innerHTML = examHtml;
        document.getElementById('reviewTotal').textContent = `NT$ ${money(totals.grandTotal)} (service fee receipt: NT$ ${money(fees.service)})`;
    }

    calculateFees();
    toggleAccommodationFields();
    document.querySelector('input[name="registration_round"]:checked')?.closest('.choice')?.classList.add('checked');
    document.querySelector('input[name="payment_method"]:checked')?.closest('.pay-opt')?.classList.add('selected');
</script>
</body>
</html>
