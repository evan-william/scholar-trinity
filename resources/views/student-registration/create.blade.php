<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('student_registration.title') }}</title>
    <style>
        :root{--primary:#1a3a6b;--primary-light:#2a5298;--accent:#c9a84c;--success:#237a4f;--danger:#b42318;--gray-50:#f8f9fa;--gray-100:#f1f3f5;--gray-200:#e9ecef;--gray-400:#ced4da;--gray-600:#6c757d;--gray-800:#343a40;--white:#fff;--radius:8px;--shadow:0 2px 16px rgba(0,0,0,.09)}
        *{box-sizing:border-box}body{margin:0;background:var(--gray-50);color:var(--gray-800);font-family:-apple-system,BlinkMacSystemFont,"Segoe UI","Microsoft JhengHei","PingFang TC",Arial,sans-serif;min-height:100vh}
        .header{background:var(--primary);color:#fff;padding:14px 24px}.head-inner{max-width:920px;margin:0 auto;display:flex;align-items:center;gap:16px;flex-wrap:wrap}.header-logos{display:flex;align-items:center;gap:12px}.logo-pill{background:#fff;color:var(--primary);font-size:10px;font-weight:800;padding:6px 10px;border-radius:6px;line-height:1.3;text-align:center;letter-spacing:.3px}.logo-divider{width:1px;height:36px;background:rgba(255,255,255,.25)}.header-title{flex:1;min-width:220px}.header-title h1{font-size:16px;font-weight:700;line-height:1.35;margin:0}.header-title p{font-size:11px;opacity:.76;margin:2px 0 0}.header-badge{background:var(--accent);color:#4b3200;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:800;white-space:nowrap}.header-actions{display:flex;gap:10px;align-items:center}
        .progress-wrap{background:#fff;border-bottom:1px solid var(--gray-200);padding:0 16px;overflow-x:auto}.progress-steps{display:flex;min-width:max-content;max-width:840px;margin:0 auto}.step-item{flex:1;display:flex;flex-direction:column;align-items:center;padding:14px 6px;position:relative;min-width:118px}.step-item:not(:last-child)::after{content:"";position:absolute;top:28px;left:calc(50% + 17px);right:calc(-50% + 17px);height:2px;background:var(--gray-200)}.step-item.completed:not(:last-child)::after{background:var(--primary)}.step-circle{width:30px;height:30px;border-radius:50%;border:2px solid var(--gray-400);background:#fff;color:var(--gray-600);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;z-index:1}.step-item.active .step-circle,.step-item.completed .step-circle{border-color:var(--primary);background:var(--primary);color:#fff}.step-label{margin-top:5px;font-size:10px;text-align:center;color:var(--gray-600);line-height:1.3}.step-item.active .step-label{color:var(--primary);font-weight:700}
        .main{max-width:760px;margin:0 auto;padding:24px 16px 108px}.card{background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);padding:26px 24px;margin-bottom:16px}.section-title{font-size:17px;font-weight:800;color:var(--primary);border-bottom:2px solid var(--accent);padding-bottom:9px;margin-bottom:20px}.section-title span{display:block;font-size:12px;font-weight:400;color:var(--gray-600);margin-top:2px}.row{display:grid;gap:14px;margin-bottom:14px}.row-2{grid-template-columns:1fr 1fr}.row-3{grid-template-columns:1fr 1fr .55fr}.row-1{grid-template-columns:1fr}.fg{display:flex;flex-direction:column;gap:5px}.fg.span2{grid-column:1/-1}.lbl{font-size:13px;font-weight:700;color:var(--gray-800)}.lbl .zh{display:block;font-size:11px;font-weight:400;color:var(--gray-600)}.req{color:var(--danger);margin-left:2px}
        input[type=text],input[type=email],input[type=tel],input[type=search],input[type=file],select,textarea{border:1.5px solid var(--gray-400);border-radius:6px;padding:9px 12px;font:inherit;font-size:14px;color:var(--gray-800);background:#fff;width:100%}input:focus,select:focus,textarea:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(26,58,107,.1)}input[aria-invalid=true],select[aria-invalid=true],textarea[aria-invalid=true]{border-color:var(--danger);background:#fffafa}.hint{font-size:11px;color:var(--gray-600);margin-top:3px}.error{color:var(--danger);font-size:12px}.error-box{background:#fff0ee;border:1px solid #ffc9c4;color:var(--danger);padding:12px 14px;border-radius:8px;margin-bottom:14px}.hidden{display:none!important}
        .upload-area{border:2px dashed var(--gray-400);border-radius:var(--radius);padding:22px 16px;text-align:center;cursor:pointer;background:var(--gray-50);position:relative}.upload-area:hover{border-color:var(--primary);background:rgba(26,58,107,.03)}.upload-area input[type=file]{position:absolute;inset:0;opacity:0;cursor:pointer;height:100%}.upload-icon{font-size:30px;margin-bottom:6px}.upload-text{font-size:13px;color:var(--gray-600)}.upload-text strong{color:var(--primary)}.upload-sub{font-size:11px;color:var(--gray-600);margin-top:3px}.upload-selected{margin-top:8px;font-size:13px;color:var(--success);font-weight:700}
        .notice{background:#fff8e1;border:1px solid #f0c040;border-left:4px solid var(--accent);border-radius:var(--radius);padding:14px 16px;margin-bottom:18px}.notice h4{font-size:13px;font-weight:800;color:#745300;margin:0 0 7px}.notice p,.notice li{font-size:12px;color:#5a4000;line-height:1.65}.notice ul{padding-left:16px;margin:0}
        .exam-sticky{position:sticky;top:0;z-index:10;background:#fff;border-bottom:1px solid var(--gray-200);padding:10px 0;margin:0 0 16px;display:flex;align-items:center;justify-content:space-between;gap:12px}.sel-badge{display:inline-flex;align-items:center;gap:6px;background:var(--primary);color:#fff;padding:4px 13px;border-radius:20px;font-size:12px;font-weight:800}.price-preview{font-size:14px;font-weight:900;color:var(--primary)}.filters{display:grid;grid-template-columns:1fr 220px;gap:10px;margin-bottom:14px}.cat-title{font-size:13px;font-weight:900;color:var(--primary);margin:18px 0 8px;padding-bottom:5px;border-bottom:1px solid var(--gray-200)}.exam-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px}.exam-cb{display:flex;align-items:flex-start;gap:10px;border:1.5px solid var(--gray-200);border-radius:8px;padding:10px 11px;cursor:pointer;background:#fff;min-height:58px}.exam-cb.checked{border-color:var(--primary);background:rgba(26,58,107,.05)}.exam-cb.disabled{opacity:.58;cursor:not-allowed;background:#f8fafc}.exam-cb input{width:16px;height:16px;min-height:auto;margin-top:2px;accent-color:var(--primary)}.exam-name{font-size:13px;font-weight:800;color:var(--gray-800);line-height:1.35}.exam-sub{font-size:11px;color:var(--gray-600);line-height:1.4}.exam-price-tag{margin-left:auto;font-size:12px;font-weight:900;color:var(--primary);white-space:nowrap}.price-box{background:var(--gray-50);border:1px solid var(--gray-200);border-radius:8px;padding:14px;margin-top:16px}.price-row{display:flex;justify-content:space-between;gap:12px;padding:5px 0;font-size:13px}.price-row.total{border-top:2px solid var(--primary);margin-top:8px;padding-top:10px;color:var(--primary);font-weight:900;font-size:16px}
        .check-line{display:flex;align-items:flex-start;gap:10px;font-size:13px;font-weight:700;line-height:1.5}.check-line input{width:18px;height:18px;min-height:auto;margin-top:1px;accent-color:var(--primary)}.ghost-btn{background:#fff;border:1.5px dashed var(--gray-400);padding:8px 14px;border-radius:6px;cursor:pointer;font-size:12px;color:var(--gray-600);font-family:inherit}.rev-section h3{font-size:14px;color:var(--primary);margin:0 0 8px}.rev-table{width:100%;border-collapse:collapse}.rev-table td{padding:7px 0;border-bottom:1px solid #edf0f5;font-size:13px;vertical-align:top}.rev-table td:first-child{width:38%;color:var(--gray-600);padding-right:12px}.div{border:0;border-top:1px solid var(--gray-200);margin:16px 0}.pay-options{display:grid;gap:10px}.pay-opt{border:1.5px solid var(--gray-200);border-radius:8px;padding:12px;display:flex;gap:10px;cursor:pointer}.pay-opt.selected{border-color:var(--primary);background:rgba(26,58,107,.05)}.pay-opt input{width:18px;height:18px;margin-top:3px;accent-color:var(--primary)}.pay-opt h4{margin:0 0 4px;color:var(--primary);font-size:14px}.pay-opt p{margin:0;color:var(--gray-600);font-size:12px;line-height:1.55}.badge-soon{display:inline-flex;background:#eef3f9;color:var(--primary);border-radius:999px;padding:2px 7px;font-size:10px}.sig-area{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:16px}.sig-box{border:1px solid var(--gray-200);border-radius:8px;padding:14px;background:var(--gray-50)}.sig-box p{font-size:12px;margin:0 0 8px}.sig-line{height:48px;border-bottom:1.5px solid var(--gray-400);margin-bottom:8px}.confirm-wrap{text-align:center;padding:10px 0}.confirm-icon{width:66px;height:66px;border-radius:50%;display:grid;place-items:center;background:#e8f6ef;color:var(--success);font-size:24px;font-weight:900;margin:0 auto 14px}.ref-box{display:inline-block;background:var(--gray-50);border:1px solid var(--gray-200);border-radius:8px;padding:13px 18px;margin:14px 0}.next-steps{text-align:left;background:var(--gray-50);border-radius:8px;padding:14px 18px;margin-top:16px}.next-steps h4{margin:0 0 8px;color:var(--primary)}.next-steps li{font-size:13px;margin-bottom:6px;line-height:1.5}
        .nav-footer{position:fixed;left:0;right:0;bottom:0;background:#fff;border-top:1px solid var(--gray-200);box-shadow:0 -2px 14px rgba(0,0,0,.06);padding:12px 18px;display:flex;align-items:center;justify-content:center;gap:18px;z-index:50}.btn{border:0;border-radius:6px;padding:11px 20px;font:inherit;font-size:14px;font-weight:900;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;justify-content:center}.btn-primary{background:var(--primary);color:#fff}.btn-primary:hover{background:var(--primary-light)}.btn-outline{background:#fff;color:var(--primary);border:1.5px solid var(--gray-400)}.btn-success{background:var(--success);color:#fff}.step-ind{font-size:12px;color:var(--gray-600);min-width:92px;text-align:center}.loading{opacity:.65;pointer-events:none}.toast{position:fixed;left:50%;bottom:82px;transform:translateX(-50%);z-index:80;max-width:min(520px,calc(100vw - 28px));background:#fff;border:1px solid #ffc9c4;border-left:5px solid var(--danger);box-shadow:0 12px 34px rgba(0,0,0,.16);border-radius:8px;padding:12px 16px;color:var(--danger);font-size:13px;font-weight:800;line-height:1.45}.toast.success{border-color:#b9e2ce;border-left-color:var(--success);color:var(--success)}
        @media(max-width:640px){.main{padding-inline:12px}.card{padding:20px 16px}.row-2,.row-3,.filters,.exam-grid,.sig-area{grid-template-columns:1fr}.header{padding-inline:16px}.header-actions{width:100%;justify-content:space-between}.nav-footer{justify-content:space-between;gap:10px}.btn{padding:10px 14px}.step-item{min-width:92px}}
        .main{max-width:900px}
        .form-intro{display:grid;grid-template-columns:minmax(0,1fr) 220px;gap:22px;align-items:center;border-top:4px solid var(--accent);overflow:hidden}
        .form-intro h2{font-size:24px;line-height:1.2;color:var(--primary);margin:10px 0 8px}
        .form-intro p{font-size:14px;line-height:1.65;color:var(--gray-600);margin:0 0 12px}
        .intro-list{margin:0;padding-left:18px;color:var(--gray-800);font-size:13px;line-height:1.65}
        .intro-poster{width:100%;border:1px solid var(--gray-200);border-radius:6px}
        @media(max-width:640px){.form-intro{grid-template-columns:1fr}.intro-poster{max-width:320px}}
        /* Current visual pass: softer registration form styling per client feedback. */
        body{background:#eef4fb}
        .header{background:linear-gradient(90deg,#102d52,#1d477d);box-shadow:0 12px 34px rgba(16,45,82,.16)}
        .head-inner{max-width:980px}
        .header-logos{gap:10px}
        .logo-pill{width:46px;height:46px;display:grid;place-items:center;background:#fff;color:var(--primary);border-radius:10px;font-size:12px;box-shadow:0 8px 22px rgba(0,0,0,.12)}
        .logo-divider{display:none}
        .brand-copy{display:flex;flex-direction:column;line-height:1.1}
        .brand-copy strong{font-size:13px;letter-spacing:.08em}
        .brand-copy span{font-size:10px;opacity:.78}
        .header-title h1{font-size:18px}
        .header-title p{font-size:12px}
        .header-badge{border-radius:999px;box-shadow:0 8px 18px rgba(201,168,76,.2)}
        .progress-wrap{background:rgba(255,255,255,.94);border-bottom:1px solid #dce6f2;box-shadow:0 6px 20px rgba(26,58,107,.06)}
        .step-item{padding:16px 8px}
        .step-circle{width:34px;height:34px;border-color:#d8e3f0;background:#f7faff;color:#6b7a90}
        .step-item.active .step-circle,.step-item.completed .step-circle{background:linear-gradient(135deg,var(--primary),var(--primary-light));border-color:transparent}
        .main{max-width:980px;padding-top:28px}
        .card{border:1px solid #dfe8f4;border-radius:14px;box-shadow:0 18px 48px rgba(26,58,107,.1);padding:28px;margin-bottom:18px}
        .section-title{border-bottom:1px solid #dfe8f4;padding-bottom:14px;margin-bottom:22px;font-size:18px}
        .section-title::after{content:"";display:block;width:62px;height:3px;background:var(--accent);border-radius:999px;margin-top:10px}
        .row{gap:16px;margin-bottom:16px}
        .fg{gap:7px}
        .lbl{font-size:12px;text-transform:none;letter-spacing:0;color:#263850}
        .lbl .zh{font-size:11px;margin-top:2px;color:#708197}
        input[type=text],input[type=email],input[type=tel],input[type=search],input[type=date],select,textarea{min-height:44px;border:1.5px solid #d8e4f2;border-radius:8px;padding:11px 13px;background:#f3f7fd;color:#1f2a37;box-shadow:inset 0 1px 0 rgba(255,255,255,.72);transition:border-color .15s ease,box-shadow .15s ease,background .15s ease}
        input[type=text]:hover,input[type=email]:hover,input[type=tel]:hover,input[type=search]:hover,input[type=date]:hover,select:hover,textarea:hover{background:#eef5ff;border-color:#c9d8ea}
        input:focus,select:focus,textarea:focus{background:#fff;border-color:#6f96c9;box-shadow:0 0 0 3px rgba(42,82,152,.13),0 8px 22px rgba(26,58,107,.08)}
        input[aria-invalid=true],select[aria-invalid=true],textarea[aria-invalid=true]{border-color:var(--danger);background:#fff7f6;box-shadow:0 0 0 3px rgba(180,35,24,.08)}
        .hint{font-size:12px}
        .upload-area{border-color:#c9d8ea;border-radius:12px;background:#f3f7fd}
        .upload-area:hover{background:#edf5ff;border-color:#6f96c9}
        .notice{border-radius:12px;background:#fffaf0}
        .exam-sticky{top:0;border-radius:10px;background:rgba(255,255,255,.95);border:1px solid #dfe8f4;padding:12px 14px;box-shadow:0 10px 24px rgba(26,58,107,.08)}
        .exam-cb,.pay-opt,.sig-box,.price-box,.next-steps{border-radius:12px}
        .exam-cb{border-color:#dbe6f3;background:#f9fbff}
        .exam-cb.checked,.pay-opt.selected{border-color:#6f96c9;background:#eef5ff;box-shadow:0 8px 20px rgba(26,58,107,.08)}
        .btn{border-radius:8px}
        .nav-footer{border-top:1px solid #dbe6f3;box-shadow:0 -12px 34px rgba(26,58,107,.12)}
        .form-intro{border:1px solid #dfe8f4;border-top:0;background:linear-gradient(135deg,#fff,#f3f7fd);grid-template-columns:minmax(0,1fr) 260px}
        .form-intro h2{font-size:28px}
        .intro-poster{border-radius:10px;background:#102d52;padding:6px;border:0;box-shadow:0 14px 34px rgba(16,45,82,.18)}
        .sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0}
        @media(max-width:640px){.main{padding-top:18px}.header-title{min-width:180px}.brand-copy{display:none}.form-intro{grid-template-columns:1fr}.intro-poster{max-width:360px}}
    </style>
</head>
<body>
<header class="header">
    <div class="head-inner">
        <div class="header-logos"><div class="logo-pill">TS</div><div class="brand-copy"><strong>TRINITY SCHOLAR</strong><span>AP Registration</span></div></div>
        <div class="header-title"><h1>{{ __('student_registration.title') }}</h1><p>{{ __('student_registration.subtitle') }}</p></div>
        <div class="header-actions"><x-language-switcher /><a class="btn btn-outline" href="{{ route('landing') }}">Landing</a><span class="header-badge">2026-2027</span></div>
    </div>
</header>
<div class="progress-wrap" aria-label="Registration progress">
    <div class="progress-steps">
        @foreach([
            ['Student Information','學生資料'],
            ['Parent / Guardian','家長 / 監護人'],
            ['Exam Selection','考試選擇'],
            ['Accommodations','特殊考試需求'],
            ['Review & Payment','確認與付款'],
            ['Confirmation','完成報名'],
        ] as $index => $label)
            <div class="step-item {{ $index === 0 ? 'active' : '' }}" data-progress="{{ $index + 1 }}">
                <div class="step-circle">{{ $index + 1 }}</div>
                <div class="step-label">{{ $label[0] }}<br>{{ $label[1] }}</div>
            </div>
        @endforeach
    </div>
</div>
<main class="main">
    @php
        $passportDraftToken = old('passport_file_token');
        $passportDraft = $passportDraftToken ? session("student_registration_passport_drafts.$passportDraftToken") : null;
        $oldAccommodations = old('accommodations', [['exam' => '', 'request' => ''], ['exam' => '', 'request' => '']]);
        $accommodationRows = max(2, count($oldAccommodations));
    @endphp

    @if ($errors->any())
        <div class="error-box" id="errorSummary"><strong>Please review the form / 請檢查表單</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <section class="card form-intro" aria-labelledby="registration-intro-title">
        <div>
            <span class="header-badge">No login required</span>
            <h2 id="registration-intro-title">2026 AP Exam Registration</h2>
            <p>Students can submit registration details, passport upload, exam selections, accommodations, and payment method in one guided flow.</p>
            <ul class="intro-list">
                <li>Late registration deadline shown in the announcement: February 10, 2026.</li>
                <li>Registration is complete only after the form and payment are received.</li>
                <li>Some Taipei test-center subjects may already be full; final availability is confirmed by the admin team.</li>
            </ul>
        </div>
        <img class="intro-poster" src="{{ asset('images/ap-late-registration-2026.jpeg') }}" alt="2026 AP Exam late registration announcement">
    </section>

    <form id="studentForm" method="POST" action="{{ route('student-registrations.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="passport_file_token" id="passportFileToken" value="{{ $passportDraftToken }}">
        <input type="hidden" name="passport_file_name" id="passportFileName" value="{{ $passportDraft['name'] ?? '' }}">
        <section data-step="1">
            <div class="card">
                <div class="section-title">Student Information <span>學生基本資料</span></div>
                <div class="row row-3">
                    <div class="fg"><label class="lbl">English Family Name <span class="req">*</span><span class="zh">英文姓氏（同護照）</span></label><input name="family_name_en" value="{{ old('family_name_en') }}" placeholder="e.g. CHEN" required @error('family_name_en') aria-invalid="true" @enderror></div>
                    <div class="fg"><label class="lbl">English First Name <span class="req">*</span><span class="zh">英文名字（同護照）</span></label><input name="first_name_en" value="{{ old('first_name_en') }}" placeholder="e.g. MING-HUA" required @error('first_name_en') aria-invalid="true" @enderror></div>
                    <div class="fg"><label class="lbl">M.I. <span class="zh">中間名縮寫</span></label><input name="middle_initial" value="{{ old('middle_initial') }}" maxlength="3" placeholder="A."></div>
                </div>
                <div class="row row-2">
                    <div class="fg"><label class="lbl">Middle Name <span class="zh">英文中間名（如適用）</span></label><input name="middle_name" value="{{ old('middle_name') }}" placeholder="e.g. ALEX"></div>
                    <div class="fg"><label class="lbl">Chinese Legal Name <span class="req">*</span><span class="zh">中文法定姓名</span></label><input name="chinese_legal_name" value="{{ old('chinese_legal_name') }}" placeholder="e.g. 陳明華" required></div>
                </div>
                <div class="row row-2">
                    <div class="fg"><label class="lbl">Date of Birth <span class="req">*</span><span class="zh">出生日期</span></label><input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required @error('date_of_birth') aria-invalid="true" @enderror></div>
                    <div class="fg"><label class="lbl">Nationality <span class="req">*</span><span class="zh">國籍</span></label><input name="nationality" value="{{ old('nationality') }}" placeholder="e.g. Taiwan" required @error('nationality') aria-invalid="true" @enderror></div>
                </div>
                <div class="row row-2">
                    <div class="fg"><label class="lbl">Passport Number <span class="req">*</span><span class="zh">護照號碼</span></label><input name="passport_number" value="{{ old('passport_number') }}" placeholder="e.g. A12345678" required @error('passport_number') aria-invalid="true" @enderror>@error('passport_number')<span class="error">{{ $message }}</span>@enderror</div>
                    <div class="fg"><label class="lbl">Passport Expiry Date <span class="zh">護照有效期限</span></label><input type="date" name="passport_expiry_date" value="{{ old('passport_expiry_date') }}" @error('passport_expiry_date') aria-invalid="true" @enderror></div>
                </div>
                <div class="row row-2">
                    <div class="fg"><label class="lbl">Grade <span class="req">*</span><span class="zh">年級</span></label><select name="grade" required><option value="">Select / 請選擇</option>@foreach($gradeLevels as $grade)<option value="{{ $grade }}" @selected(old('grade')===$grade || old('grade_level')===$grade)>Grade {{ $grade }} / {{ $grade }} 年級</option>@endforeach</select></div>
                    <div class="fg"><label class="lbl">Current School <span class="req">*</span><span class="zh">目前就讀學校</span></label><input name="current_school" value="{{ old('current_school', old('school_name')) }}" placeholder="e.g. Taipei International School" required></div>
                </div>
                <input type="hidden" name="school_country" value="{{ old('school_country', 'Taiwan') }}">
                <div class="row row-2">
                    <div class="fg"><label class="lbl">Student Email <span class="req">*</span><span class="zh">學生電子郵件</span></label><input type="email" name="student_email" value="{{ old('student_email') }}" placeholder="student@example.com" required @error('student_email') aria-invalid="true" @enderror>@error('student_email')<span class="error">{{ $message }}</span>@enderror</div>
                    <div class="fg"><label class="lbl">Student Phone <span class="req">*</span><span class="zh">學生電話</span></label><input type="tel" name="student_phone" value="{{ old('student_phone') }}" placeholder="0912345678" required></div>
                </div>
                <div class="row row-1">
                    <div class="fg">
                        <label class="lbl">Passport Upload <span class="req">*</span><span class="zh">護照上傳（照片頁需清楚）</span></label>
                        <div class="upload-area">
                            <input type="file" name="passport_file" id="passportFile" accept=".pdf,.jpg,.jpeg,.png">
                            <div class="upload-icon">Upload</div>
                            <div class="upload-text"><strong>Click to upload / 點選上傳</strong> or drag & drop</div>
                            <div class="upload-sub">PDF, JPG, PNG / Max 10MB / 請上傳清楚的護照照片頁</div>
                            <div class="upload-selected {{ $passportDraft ? '' : 'hidden' }}" id="fileLabel">
                                @if($passportDraft)
                                    Selected / 已選擇: {{ $passportDraft['name'] }}
                                @endif
                            </div>
                        </div>
                        @if($passportDraft)
                            <span class="hint">This file is saved temporarily after the validation error. Upload a new file only if you want to replace it.</span>
                        @endif
                        @error('passport_file')<span class="error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
            <div class="notice"><h4>Important Notice / 重要提醒</h4><ul><li>Except AP Chinese, late or exception exam sessions are not offered. / 除 AP 中文外，不提供補考或例外考試場次。</li><li>Once payment is submitted, cancelled exams are non-refundable. / 繳費後取消考試恕不退費。</li></ul></div>
        </section>

        <section class="hidden" data-step="2">
            <div class="card">
                <div class="section-title">Parent / Guardian Information <span>家長 / 監護人資料</span></div>
                <div class="row row-2"><div class="fg"><label class="lbl">Parent First Name <span class="req">*</span><span class="zh">家長名字</span></label><input name="parent_first_name" value="{{ old('parent_first_name') }}" required></div><div class="fg"><label class="lbl">Parent Last Name <span class="req">*</span><span class="zh">家長姓氏</span></label><input name="parent_last_name" value="{{ old('parent_last_name') }}" required></div></div>
                <div class="row row-1"><div class="fg"><label class="lbl">Relationship to Student <span class="req">*</span><span class="zh">與學生關係</span></label><input name="relationship" value="{{ old('relationship') }}" placeholder="Mother, Father, Guardian" required></div></div>
                <div class="row row-2"><div class="fg"><label class="lbl">Parent Email <span class="req">*</span><span class="zh">家長電子郵件</span></label><input type="email" name="parent_email" value="{{ old('parent_email') }}" required></div><div class="fg"><label class="lbl">Parent Phone <span class="req">*</span><span class="zh">家長電話</span></label><input type="tel" name="parent_phone" value="{{ old('parent_phone') }}" required></div></div>
                <div class="row row-1"><div class="fg"><label class="lbl">Mailing Address <span class="req">*</span><span class="zh">通訊地址</span></label><input name="mailing_address" value="{{ old('mailing_address') }}" required></div></div>
                <div class="row row-2"><div class="fg"><label class="lbl">City / District <span class="req">*</span><span class="zh">城市 / 區域</span></label><input name="mailing_city" value="{{ old('mailing_city') }}" required></div><div class="fg"><label class="lbl">Postal Code <span class="zh">郵遞區號</span></label><input name="postal_code" value="{{ old('postal_code') }}" maxlength="12"></div></div>
            </div>
            <div class="card">
                <div class="section-title">Emergency Contact <span>緊急聯絡人</span></div>
                <div class="row row-2"><div class="fg"><label class="lbl">Name <span class="req">*</span><span class="zh">姓名</span></label><input name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" required></div><div class="fg"><label class="lbl">Phone <span class="req">*</span><span class="zh">電話</span></label><input type="tel" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" required></div></div>
                <div class="row row-1"><div class="fg"><label class="lbl">Emergency Contact Relationship <span class="req">*</span><span class="zh">緊急聯絡人關係</span></label><input name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship') }}" placeholder="Father, Mother, Guardian" required></div></div>
            </div>
        </section>

        <section class="hidden" data-step="3">
            <div class="card">
                <div class="section-title">AP Exam Selection <span>AP 考試選擇與費用</span></div>
                <div class="exam-sticky"><span id="selBadge" class="sel-badge">0 selected / 已選 0 科</span><span id="pricePreview" class="price-preview">NT$ 0</span></div>
                <div class="filters"><label class="lbl">Search exam <span class="zh">搜尋考科</span><input id="examSearch" type="search" placeholder="Calculus, Biology, CSA"></label><label class="lbl">AP exam categories <span class="zh">考科分類</span><select id="categoryFilter"><option value="">All Categories / 全部分類</option>@foreach($subjects->pluck('category')->filter()->unique()->sort() as $category)<option value="{{ $category }}">{{ $category }}</option>@endforeach</select></label></div>
                @foreach($subjects->groupBy(fn($subject) => $subject->category ?: 'Other') as $category => $categorySubjects)
                    <div class="cat-title">{{ $category }} / {{ $category === 'Mathematics' ? '數學' : ($category === 'Science' || $category === 'Sciences' ? '科學' : '分類') }}</div>
                    <div class="exam-grid">
                        @foreach($categorySubjects as $subject)
                            @php($selectable = $subject->isSelectable())
                            @php($lateFee = $subject->lateFeeApplies() ? $subject->late_registration_fee : 0)
                            @php($statusKey = strtolower($subject->status))
                            <label class="exam-cb regular-exam {{ $selectable ? '' : 'disabled' }}">
                                <input type="checkbox" name="exam_subject_uuids[]" value="{{ $subject->uuid }}" data-type="regular" data-p="{{ $subject->exam_fee + $subject->service_fee + $lateFee }}" data-exam-fee="{{ $subject->exam_fee }}" data-service-fee="{{ $subject->service_fee }}" data-late-fee="{{ $lateFee }}" data-name="{{ $subject->name }}" data-category="{{ $subject->category }}" @disabled(! $selectable) @checked(in_array($subject->uuid, old('exam_subject_uuids', [])))>
                                <div>
                                    <div class="exam-name">{{ $subject->name }}</div>
                                    <div class="exam-sub">{{ $subject->code }} / {{ optional($subject->exam_date)->format('M d, Y') ?? 'Date TBA' }} / {{ __('student_registration.statuses.'.$statusKey) }}</div>
                                    <div class="exam-sub">Exam NT$ {{ number_format($subject->exam_fee) }} / Service NT$ {{ number_format($subject->service_fee) }} / Late NT$ {{ number_format($lateFee) }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @endforeach
                <div class="section-title" style="margin-top:24px">Practice Exams (Optional) <span>模擬考（選填） / NT$1,800 per exam</span></div>
                <div class="notice"><h4>Practice Exam Info / 模擬考說明</h4><p>NT$1,800 per practice exam. Dates subject to change. / 每科模擬考 NT$1,800，日期可能調整。</p></div>
                <div class="exam-grid">
                    @foreach(['Biology 生物','English Language and Composition 英文語言與寫作','Physics 1 物理 1','Computer Science A 電腦科學 A','Calculus AB/BC 微積分','Macroeconomics 總體經濟','Precalculus 預備微積分'] as $practice)
                        <label class="exam-cb"><input type="checkbox" name="practice_exams[]" value="{{ $practice }}" data-type="practice" data-p="1800" data-name="Practice: {{ $practice }}" @checked(in_array($practice, old('practice_exams', [])))><div><div class="exam-name">{{ $practice }}</div><div class="exam-sub">Practice / 模擬考</div></div><div class="exam-price-tag">NT$1,800</div></label>
                    @endforeach
                </div>
                <input type="hidden" name="practice_exam_total" id="practiceExamTotal" value="0">
                <div class="price-box">
                    <div class="price-row"><span>Regular AP Exams / 正式考試 (<span id="regCt">0</span>)</span><span>NT$ <span id="regTot">0</span></span></div>
                    <div class="price-row"><span>Practice Exams / 模擬考 (<span id="praCt">0</span>)</span><span>NT$ <span id="praTot">0</span></span></div>
                    <div class="price-row"><span>Late Registration Fee / 逾期報名費</span><span>NT$ <span id="lateTot">0</span></span></div>
                    <div class="price-row total"><span>Total Due / 應付總額</span><span>NT$ <span id="grandTot">0</span></span></div>
                    <p class="hint">Final pricing confirmed by AP Coordinator. / 最終費用由 AP 協調員確認。</p>
                </div>
            </div>
        </section>

        <section class="hidden" data-step="4">
            <div class="card">
                <div class="section-title">Testing Accommodations <span>特殊考試需求</span></div>
                <div class="notice"><h4>About Accommodations / 關於特殊需求</h4><p>If you qualify for extra time, food/medication, reader/scribe, or other approved accommodations, please contact the AP Coordinator first. / 如需延長時間、藥物或其他核准協助，請先聯繫 AP 協調員。</p></div>
                <label class="check-line" style="margin-bottom:18px"><input type="checkbox" name="needs_accommodations" value="1" id="needsAccom" @checked(old('needs_accommodations'))><span>I am requesting testing accommodations / 我需要申請特殊考試需求</span></label>
                <div id="accomFields" class="hidden">
                    <div class="row row-2"><div class="fg"><label class="lbl">SSD Code <span class="zh">College Board SSD 代碼</span></label><input name="ssd_code" value="{{ old('ssd_code') }}" placeholder="SSD Code"></div><div class="fg"><label class="lbl">Approval Status <span class="zh">核准狀態</span></label><select name="accommodation_status"><option value="">Select / 請選擇</option><option value="approved" @selected(old('accommodation_status') === 'approved')>Already Approved / 已核准</option><option value="pending" @selected(old('accommodation_status') === 'pending')>Pending / 審核中</option><option value="new" @selected(old('accommodation_status') === 'new')>New Request / 新申請</option></select></div></div>
                    <label class="lbl" style="margin-bottom:8px;display:block">Exam Name and Requested Accommodation Rows / 考科與申請內容</label>
                    <div id="accomRows">
                        @for($i = 0; $i < $accommodationRows; $i++)
                            <div class="row row-2 accom-row"><div class="fg"><input name="accommodations[{{ $i }}][exam]" value="{{ old("accommodations.$i.exam") }}" placeholder="Exam name / 考科名稱"></div><div class="fg"><input name="accommodations[{{ $i }}][request]" value="{{ old("accommodations.$i.request") }}" placeholder="Accommodation requested / 申請項目"></div></div>
                        @endfor
                    </div>
                    <button type="button" class="ghost-btn" id="addAccomRow">+ Add row / 新增一列</button>
                </div>
            </div>
        </section>

        <section class="hidden" data-step="5">
            <div class="card">
                <div class="section-title">Review Your Registration <span>確認報名資料</span></div>
                <div class="rev-section"><h3>Student Information / 學生資料</h3><table class="rev-table"><tr><td>Legal Name (EN)</td><td id="rName">-</td></tr><tr><td>Chinese Name / 中文姓名</td><td id="rCn">-</td></tr><tr><td>Date of Birth</td><td id="rDob">-</td></tr><tr><td>Nationality</td><td id="rNationality">-</td></tr><tr><td>Grade / 年級</td><td id="rGrade">-</td></tr><tr><td>School / 學校</td><td id="rSchool">-</td></tr><tr><td>Student Email</td><td id="rSEmail">-</td></tr><tr><td>Student Phone</td><td id="rSPhone">-</td></tr><tr><td>Passport Number</td><td id="rPassNo">-</td></tr><tr><td>Passport File / 護照</td><td id="rPass">-</td></tr></table></div>
                <hr class="div">
                <div class="rev-section"><h3>Parent Information / 家長資料</h3><table class="rev-table"><tr><td>Parent Name</td><td id="rPName">-</td></tr><tr><td>Relationship</td><td id="rRel">-</td></tr><tr><td>Parent Email</td><td id="rPEmail">-</td></tr><tr><td>Parent Phone</td><td id="rPPhone">-</td></tr><tr><td>Mailing Address</td><td id="rAddr">-</td></tr><tr><td>Emergency Contact</td><td id="rEmergency">-</td></tr></table></div>
                <hr class="div">
                <div class="rev-section"><h3>Selected Exams / 已選考科</h3><div id="rExams" class="hint" style="margin-top:8px">-</div></div>
                <hr class="div">
                <div class="rev-section"><h3>Accommodations / 特殊需求</h3><div id="rAccom" class="hint" style="margin-top:8px">-</div></div>
                <hr class="div">
                <div class="rev-section"><h3>Fee Summary / 費用摘要</h3><table class="rev-table"><tr><td>Regular Exams / 正式考試</td><td id="rReg">-</td></tr><tr><td>Practice Exams / 模擬考</td><td id="rPra">-</td></tr><tr><td>Late Fee / 逾期費</td><td id="rLate">-</td></tr><tr style="font-weight:800;color:var(--primary)"><td>Total / 總計</td><td id="rTot">-</td></tr></table></div>
            </div>
            <div class="card">
                <div class="section-title">Payment Method <span>付款方式</span></div>
                <div class="pay-options">
                    <label class="pay-opt"><input type="radio" name="payment_method" value="bank_transfer" required @checked(old('payment_method') === 'bank_transfer')><div><h4>Bank Transfer / 銀行轉帳</h4><p>Transfer to school bank account, then confirm with School Cashier WaWa Wang. / 匯款至學校帳戶後，請向出納確認。</p></div></label>
                    <label class="pay-opt"><input type="radio" name="payment_method" value="cash" @checked(old('payment_method') === 'cash')><div><h4>Cash / 現金</h4><p>Direct cash payment to school cashier. / 直接至學校出納繳現金。</p></div></label>
                    <label class="pay-opt"><input type="radio" name="payment_method" value="credit_card" @checked(old('payment_method') === 'credit_card' || old('payment_method') === 'online')><div><h4>Credit Card / 信用卡 <span class="badge-soon">Gateway Setup Required / 需設定金流</span></h4><p>Available after ECPay or NewebPay credentials are configured. / 設定金流商資料後可使用。</p></div></label>
                    <label class="pay-opt"><input type="radio" name="payment_method" value="atm" @checked(old('payment_method') === 'atm')><div><h4>ATM Transfer / ATM 轉帳 <span class="badge-soon">Gateway Setup Required / 需設定金流</span></h4><p>Available after the selected Taiwan gateway enables ATM payment. / 金流商啟用 ATM 後可使用。</p></div></label>
                </div>
                <div class="notice" style="margin-top:16px"><h4>Acknowledgement / 聲明確認</h4><ul><li>All information provided is accurate and complete. / 所填資料正確且完整。</li><li>I understand there are no refunds once payment is made. / 繳費後恕不退費。</li><li>I have verified the exam schedule for conflicts. / 我已確認考試時程無衝突。</li></ul></div>
                <div class="sig-area"><div class="sig-box"><p><strong>Student Signature / 學生簽名</strong></p><div class="sig-line"></div><p>Date: _____________</p></div><div class="sig-box"><p><strong>Parent / Guardian Signature / 家長簽名</strong></p><div class="sig-line"></div><p>Date: _____________</p></div></div>
                <div style="margin-top:18px"><label class="check-line"><input type="checkbox" name="confirmed_review" value="1" required @checked(old('confirmed_review'))><span>I have read and agree to the terms above. / 我已閱讀並同意以上條款 <span class="req">*</span></span></label></div>
                <input type="hidden" name="accurate_information" value="1"><input type="hidden" name="ap_policies" value="1"><input type="hidden" name="privacy_policy" value="1"><input type="hidden" name="terms_conditions" value="1">
            </div>
        </section>

        <section class="hidden" data-step="6">
            <div class="card">
                <div class="confirm-wrap">
                    <div class="confirm-icon">OK</div>
                    <h2>Registration Submitted! / 報名已送出</h2>
                    <p>Your AP Exam registration is ready to submit. After submission, the AP Coordinator will review it and contact you to confirm payment.<br><br>您的 AP 考試報名即將送出。送出後 AP 協調員將審核並聯繫您確認付款。</p>
                    <div class="ref-box">Reference No. / 參考編號<br><strong>Generated after submission / 送出後產生</strong></div>
                    <p class="hint">Confirmation email will be sent to / 確認信將寄至 <strong id="confEmail">-</strong></p>
                    <div class="next-steps"><h4>Next Steps / 後續步驟</h4><ol><li>AP Coordinator verifies your registration / AP 協調員審核報名資料</li><li>Complete payment by the registration deadline / 在截止日前完成付款</li><li>Confirm payment with school cashier / 向學校出納確認款項</li><li>Watch your email for exam schedule details / 留意電子郵件中的考試時程</li></ol></div>
                </div>
            </div>
        </section>
    </form>
</main>
<div class="nav-footer" id="navFooter">
    <button class="btn btn-outline" id="btnBack" type="button" style="visibility:hidden">Back / 上一步</button>
    <span class="step-ind" id="stepInd">Step 1 of 6</span>
    <button class="btn btn-primary" id="btnNext" type="button">Next / 下一步</button>
</div>
<div class="toast hidden" id="formToast" role="status" aria-live="polite"></div>
<script>
    const initialStep = Number(@json(session('student_registration_error_step', 1)));
    const preservedPassportName = @json($passportDraft['name'] ?? null);
    const passportDraftUrl = @json(route('student-registrations.passport-draft'));
    const draftKey = 'studentRegistrationFormDraft';
    let cur = 1;
    const totalSteps = 6;
    const form = document.getElementById('studentForm');
    const money = value => Number(value || 0).toLocaleString('en-US');
    const field = name => form.elements[name]?.value || '';
    const checkedExams = () => [...document.querySelectorAll('.exam-cb input[type="checkbox"]:checked')];
    let toastTimer;

    function notify(message, type = 'error') {
        const toast = document.getElementById('formToast');
        toast.textContent = message;
        toast.classList.toggle('success', type === 'success');
        toast.classList.remove('hidden');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => toast.classList.add('hidden'), 3600);
    }

    function readFormDraft() {
        try {
            return JSON.parse(localStorage.getItem(draftKey) || '{}');
        } catch {
            return {};
        }
    }

    function saveFormDraft() {
        const data = {};
        const grouped = new Map();

        [...form.elements].forEach(input => {
            if (!input.name || input.type === 'file' || input.name === '_token') return;

            if (input.type === 'checkbox') {
                if (input.name.endsWith('[]')) {
                    const values = grouped.get(input.name) || [];
                    if (input.checked) values.push(input.value);
                    grouped.set(input.name, values);
                } else {
                    data[input.name] = input.checked ? input.value : '';
                }
                return;
            }

            if (input.type === 'radio') {
                if (input.checked) data[input.name] = input.value;
                return;
            }

            data[input.name] = input.value;
        });

        grouped.forEach((values, name) => data[name] = values);
        data.__step = cur;
        localStorage.setItem(draftKey, JSON.stringify(data));
    }

    function restoreFormDraft() {
        const data = readFormDraft();
        if (!Object.keys(data).length || document.getElementById('errorSummary')) return null;

        [...form.elements].forEach(input => {
            if (!input.name || input.type === 'file' || input.name === '_token' || !(input.name in data)) return;

            if (input.type === 'checkbox') {
                input.checked = Array.isArray(data[input.name])
                    ? data[input.name].includes(input.value)
                    : data[input.name] === input.value;
                return;
            }

            if (input.type === 'radio') {
                input.checked = data[input.name] === input.value;
                return;
            }

            input.value = data[input.name] ?? '';
        });

        return Number(data.__step || 1);
    }

    function setPassportLabel(name, pending = false) {
        const label = document.getElementById('fileLabel');
        label.textContent = name ? `${pending ? 'Saving / 暫存中' : 'Selected / 已選擇'}: ${name}` : '';
        label.classList.toggle('hidden', !name);
    }

    async function savePassportDraft(file) {
        const body = new FormData();
        body.append('passport_file', file);

        const response = await fetch(passportDraftUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': form.elements['_token'].value,
                'Accept': 'application/json',
            },
            body,
        });

        if (!response.ok) {
            const payload = await response.json().catch(() => ({}));
            const message = payload.message || Object.values(payload.errors || {})[0]?.[0] || 'Unable to save passport file draft.';
            throw new Error(message);
        }

        return response.json();
    }

    function setStep(next) {
        document.querySelectorAll('[data-step]').forEach(section => section.classList.add('hidden'));
        document.querySelector(`[data-step="${next}"]`).classList.remove('hidden');
        cur = next;
        document.querySelectorAll('[data-progress]').forEach(item => {
            const index = Number(item.dataset.progress);
            item.classList.toggle('active', index === cur);
            item.classList.toggle('completed', index < cur);
            item.querySelector('.step-circle').textContent = index < cur ? '✓' : index;
        });
        document.getElementById('btnBack').style.visibility = cur === 1 ? 'hidden' : 'visible';
        document.getElementById('stepInd').textContent = `Step ${cur} of ${totalSteps}`;
        document.getElementById('btnNext').textContent = cur === totalSteps ? 'Submit / 送出' : 'Next / 下一步';
        document.getElementById('btnNext').className = cur === totalSteps ? 'btn btn-success' : 'btn btn-primary';
        if (cur === 5 || cur === 6) buildReview();
        window.scrollTo({top:0, behavior:'smooth'});
    }

    function validateStep() {
        const section = document.querySelector(`[data-step="${cur}"]`);
        for (const input of section.querySelectorAll('[required]')) {
            if (!input.checkValidity()) {
                input.reportValidity();
                return false;
            }
        }
        const selectedRegularExams = [...form.querySelectorAll('input[name="exam_subject_uuids[]"]')]
            .filter(input => input.checked && !input.disabled);
        if (cur === 3 && selectedRegularExams.length === 0) {
            notify('Please select at least one AP exam. / 請至少選擇一科 AP 考試。');
            return false;
        }
        return true;
    }

    function calculate() {
        let regCt = 0, praCt = 0, regTot = 0, praTot = 0, lateTot = 0;
        document.querySelectorAll('.exam-cb input[type="checkbox"]').forEach(input => {
            input.closest('.exam-cb').classList.toggle('checked', input.checked);
            if (!input.checked) return;
            if (input.dataset.type === 'practice') {
                praCt++;
                praTot += Number(input.dataset.p || 0);
            } else {
                regCt++;
                regTot += Number(input.dataset.examFee || input.dataset.p || 0) + Number(input.dataset.serviceFee || 0);
                lateTot += Number(input.dataset.lateFee || 0);
            }
        });
        const grand = regTot + praTot + lateTot;
        document.getElementById('regCt').textContent = regCt;
        document.getElementById('praCt').textContent = praCt;
        document.getElementById('regTot').textContent = money(regTot);
        document.getElementById('praTot').textContent = money(praTot);
        document.getElementById('lateTot').textContent = money(lateTot);
        document.getElementById('grandTot').textContent = money(grand);
        document.getElementById('selBadge').textContent = `${regCt + praCt} selected / 已選 ${regCt + praCt} 科`;
        document.getElementById('pricePreview').textContent = `NT$ ${money(grand)}`;
        document.getElementById('practiceExamTotal').value = praTot;
        return {regCt, praCt, regTot, praTot, lateTot, grand};
    }

    function buildReview() {
        const totals = calculate();
        const names = checkedExams().map(input => input.dataset.name);
        const passport = document.getElementById('passportFile').files[0]?.name || document.getElementById('passportFileName').value || preservedPassportName || 'Uploaded file / 已選擇檔案';
        document.getElementById('rName').textContent = [field('family_name_en'), field('first_name_en'), field('middle_name')].filter(Boolean).join(' ');
        document.getElementById('rCn').textContent = field('chinese_legal_name') || '-';
        document.getElementById('rDob').textContent = field('date_of_birth') || '-';
        document.getElementById('rNationality').textContent = field('nationality') || '-';
        document.getElementById('rGrade').textContent = field('grade') || '-';
        document.getElementById('rSchool').textContent = field('current_school') || '-';
        document.getElementById('rSEmail').textContent = field('student_email') || '-';
        document.getElementById('rSPhone').textContent = field('student_phone') || '-';
        document.getElementById('rPassNo').textContent = field('passport_number') || '-';
        document.getElementById('rPass').textContent = passport;
        document.getElementById('rPName').textContent = [field('parent_first_name'), field('parent_last_name')].filter(Boolean).join(' ');
        document.getElementById('rRel').textContent = field('relationship') || '-';
        document.getElementById('rPEmail').textContent = field('parent_email') || '-';
        document.getElementById('rPPhone').textContent = field('parent_phone') || '-';
        document.getElementById('rAddr').textContent = [field('mailing_address'), field('mailing_city'), field('postal_code')].filter(Boolean).join(', ');
        document.getElementById('rEmergency').textContent = [field('emergency_contact_name'), field('emergency_contact_phone'), field('emergency_contact_relationship')].filter(Boolean).join(' / ') || '-';
        document.getElementById('rExams').textContent = names.length ? names.join(', ') : '-';
        document.getElementById('rAccom').textContent = document.getElementById('needsAccom').checked
            ? [field('ssd_code'), field('accommodation_status')].filter(Boolean).join(' / ') || 'Requested'
            : 'No accommodations requested';
        document.getElementById('rReg').textContent = `${totals.regCt} exams / NT$ ${money(totals.regTot)}`;
        document.getElementById('rPra').textContent = `${totals.praCt} exams / NT$ ${money(totals.praTot)}`;
        document.getElementById('rLate').textContent = `NT$ ${money(totals.lateTot)}`;
        document.getElementById('rTot').textContent = `NT$ ${money(totals.grand)}`;
        document.getElementById('confEmail').textContent = field('student_email') || '-';
    }

    document.querySelectorAll('.exam-cb').forEach(label => {
        label.addEventListener('click', event => {
            const input = label.querySelector('input[type="checkbox"]');
            if (!input || input.disabled || event.target === input) return;
            event.preventDefault();
            input.checked = !input.checked;
            input.dispatchEvent(new Event('change', { bubbles: true }));
        });
    });
    document.querySelectorAll('.exam-cb input').forEach(input => input.addEventListener('change', calculate));
    document.querySelectorAll('.pay-opt input').forEach(input => input.addEventListener('change', () => {
        document.querySelectorAll('.pay-opt').forEach(label => label.classList.toggle('selected', label.querySelector('input').checked));
    }));
    document.getElementById('passportFile').addEventListener('change', async event => {
        const file = event.target.files[0];
        document.getElementById('passportFileToken').value = '';
        document.getElementById('passportFileName').value = '';

        if (!file) {
            setPassportLabel('');
            saveFormDraft();
            return;
        }

        setPassportLabel(file.name, true);

        try {
            const draft = await savePassportDraft(file);
            document.getElementById('passportFileToken').value = draft.token;
            document.getElementById('passportFileName').value = draft.name;
            setPassportLabel(draft.name);
            saveFormDraft();
        } catch (error) {
            setPassportLabel('');
            event.target.value = '';
            notify(error.message);
            saveFormDraft();
        }
    });
    document.getElementById('needsAccom').addEventListener('change', event => document.getElementById('accomFields').classList.toggle('hidden', !event.target.checked));
    document.getElementById('addAccomRow').addEventListener('click', () => {
        const index = document.querySelectorAll('.accom-row').length;
        const row = document.createElement('div');
        row.className = 'row row-2 accom-row';
        row.innerHTML = `<div class="fg"><input name="accommodations[${index}][exam]" placeholder="Exam name / 考科名稱"></div><div class="fg"><input name="accommodations[${index}][request]" placeholder="Accommodation requested / 申請項目"></div>`;
        document.getElementById('accomRows').appendChild(row);
    });
    function filterExams() {
        const query = document.getElementById('examSearch').value.toLowerCase();
        const category = document.getElementById('categoryFilter').value;
        document.querySelectorAll('.regular-exam').forEach(label => {
            const input = label.querySelector('input');
            label.style.display = label.textContent.toLowerCase().includes(query) && (!category || input.dataset.category === category) ? '' : 'none';
        });
    }
    document.getElementById('examSearch').addEventListener('input', filterExams);
    document.getElementById('categoryFilter').addEventListener('change', filterExams);
    document.getElementById('btnNext').addEventListener('click', () => {
        if (!validateStep()) return;
        if (cur < totalSteps) {
            setStep(cur + 1);
            saveFormDraft();
            return;
        }
        const btn = document.getElementById('btnNext');
        btn.classList.add('loading');
        btn.textContent = 'Submitting / 送出中';
        localStorage.removeItem(draftKey);
        form.requestSubmit();
    });
    document.getElementById('btnBack').addEventListener('click', () => {
        setStep(Math.max(1, cur - 1));
        saveFormDraft();
    });
    form.addEventListener('input', saveFormDraft);
    form.addEventListener('change', saveFormDraft);
    const restoredStep = restoreFormDraft();
    if (!preservedPassportName && document.getElementById('passportFileName').value) {
        setPassportLabel(document.getElementById('passportFileName').value);
    }
    document.querySelectorAll('.pay-opt').forEach(label => label.classList.toggle('selected', label.querySelector('input').checked));
    calculate();
    if (document.getElementById('needsAccom').checked) document.getElementById('accomFields').classList.remove('hidden');
    if (initialStep > 1 && initialStep <= totalSteps) {
        setStep(initialStep);
    } else if (restoredStep > 1 && restoredStep <= totalSteps) {
        setStep(restoredStep);
    }
</script>
</body>
</html>
