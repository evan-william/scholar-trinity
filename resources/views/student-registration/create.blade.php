@php
    $brandLogo = 'images/trinity-scholar-logo.png';
    $footerLogo = 'images/trinity-scholar-logo.png';
    $brandFavicon = 'images/trinity-scholar-favicon.png';
    $uiLocale = session('locale', str_replace('_', '-', app()->getLocale()));
    $isZh = $uiLocale === 'zh-TW';
    $navLabels = $isZh
        ? ['home' => '首頁', 'program' => '課程資訊', 'timeline' => '時程', 'fees' => '費用', 'faq' => '常見問題', 'contact' => '聯絡我們', 'start' => '開始報名', 'support' => '台北 AP 報名支援']
        : ['home' => 'Home', 'program' => 'Program', 'timeline' => 'Timeline', 'fees' => 'Fees', 'faq' => 'FAQ', 'contact' => 'Contact', 'start' => 'Start Form', 'support' => 'Taipei AP Registration Support'];
    $stepLabels = $isZh
        ? ['學生資料', '家長 / 監護人', '考試選擇', '特殊考試需求', '確認與付款', '完成報名']
        : ['Student Information', 'Parent / Guardian', 'Exam Selection', 'Accommodations', 'Review & Payment', 'Confirmation'];
    $introCopy = $isZh
        ? [
            'badge' => '不需登入',
            'title' => '2026 AP 考試報名',
            'body' => '學生可在同一個流程中提交報名資料、護照、考試選擇、特殊需求與付款方式。',
            'items' => ['逾期報名截止日期：2026 年 2 月 10 日。', '報名需在表單與付款皆收到後才算完成。', 'AP Chinese、AP Calculus、AP Macro/Micro 已在台北考場公告中標示額滿。', '最終科目名額將由管理團隊審核後確認。'],
            'summary_label' => '逾期報名',
            'summary_title' => '2 月 10 日',
            'summary_body' => '逾期報名可能會有額外費用。座位有限，額滿時可能提前關閉報名。',
        ]
        : [
            'badge' => 'No login required',
            'title' => '2026 AP Exam Registration',
            'body' => 'Students can submit registration details, passport upload, exam selections, accommodations, and payment method in one guided flow.',
            'items' => ['Late registration deadline: February 10, 2026.', 'Registration is complete only after the form and payment are received.', 'AP Chinese, AP Calculus, and AP Macro/Micro are marked full in the shared Taipei test-center notice.', 'Final subject availability is confirmed by the admin team after submission.'],
            'summary_label' => 'Late Registration',
            'summary_title' => 'Feb. 10',
            'summary_body' => 'Extra late registration fees may apply. Submit early because registration can close before the deadline when seats are full.',
        ];
    $footerLabels = $isZh
        ? [
            'office' => '服務說明',
            'office_body' => '台北考場 AP 報名支援。',
            'phone' => '聯絡電話',
            'email' => '電子郵件',
            'registration' => '報名資訊',
            'program' => '課程資訊',
            'timeline' => '報名時程',
            'fees' => '費用說明',
            'register' => '立即報名',
            'notice' => '重要提醒',
            'notice_body' => '報名需在表單與付款皆收到後才算完成。名額有限，可能在公告截止日前額滿關閉。',
            'main_period' => '一般時段：',
            'late_period' => '逾期時段：',
            'deadline' => '截止日期：',
            'main_period_value' => '八月至十月',
            'late_period_value' => '一月至三月',
            'deadline_value' => '本次逾期報名公告為 2026 年 2 月 10 日',
            'copyright' => '版權所有',
            'rights' => '保留所有權利。',
            'designed' => 'Designed By',
            'powered' => 'Powered by',
        ]
        : [
            'office' => 'Office Address',
            'office_body' => 'Taipei test-center AP registration support.',
            'phone' => 'Business Phone',
            'email' => 'Business Email',
            'registration' => 'Registration',
            'program' => 'Program Information',
            'timeline' => 'Timeline',
            'fees' => 'Fees',
            'register' => 'Register Now',
            'notice' => 'Important Notice',
            'notice_body' => 'Registration is complete only after the filled-out form and payment are received. Available seats may close before the listed deadline.',
            'main_period' => 'Main Period :',
            'late_period' => 'Late Period :',
            'deadline' => 'Deadline :',
            'main_period_value' => 'August - October',
            'late_period_value' => 'January - March',
            'deadline_value' => 'February 10, 2026 for the current late-registration notice',
            'copyright' => 'Copyright',
            'rights' => 'All Rights Reserved.',
            'designed' => 'Designed By',
            'powered' => 'Powered by',
        ];
    $tx = fn (string $en, string $zh): string => $isZh ? $zh : $en;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('student_registration.title') }}</title>
    <link rel="icon" type="image/png" href="{{ asset($brandFavicon) }}">
    <link rel="stylesheet" href="{{ asset('theme/edification/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/edification/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/edification/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/edification/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/edification/css/slicknav.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/edification/css/typography.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/edification/css/default-css.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/edification/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/edification/css/responsive.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('theme/trinity/css/public-ui.css') }}?v=20260715-1">
    <script src="{{ asset('theme/edification/js/vendor/modernizr-2.8.3.min.js') }}"></script>
    <style>
        :root{--trinity-blue:#244e9a;--trinity-blue-dark:#142f63;--trinity-blue-soft:#eaf2ff;--trinity-blue-bright:#9db9ff;--primary:#244e9a;--primary-light:#142f63;--accent:#244e9a;--success:#237a4f;--danger:#b42318;--gray-50:#f8f9fa;--gray-100:#f1f3f5;--gray-200:#e9ecef;--gray-400:#ced4da;--gray-600:#6c757d;--gray-800:#343a40;--white:#fff;--radius:8px;--shadow:0 2px 16px rgba(0,0,0,.09)}
        html{scroll-behavior:smooth}
        *{box-sizing:border-box}body{margin:0;background:var(--gray-50);color:var(--gray-800);font-family:"Open Sans","Microsoft JhengHei","PingFang TC",Arial,sans-serif;min-height:100vh}
        .header{background:var(--primary);color:#fff;padding:14px 24px}.head-inner{max-width:920px;margin:0 auto;display:flex;align-items:center;gap:16px;flex-wrap:wrap}.header-logos{display:flex;align-items:center;gap:12px}.logo-pill{background:#fff;color:var(--primary);font-size:10px;font-weight:800;padding:6px 10px;border-radius:6px;line-height:1.3;text-align:center;letter-spacing:.3px}.logo-divider{width:1px;height:36px;background:rgba(255,255,255,.25)}.header-title{flex:1;min-width:220px}.header-title h1{font-size:16px;font-weight:700;line-height:1.35;margin:0}.header-title p{font-size:11px;opacity:.76;margin:2px 0 0}.header-badge{background:var(--trinity-blue);color:#fff;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:800;white-space:nowrap}.header-actions{display:flex;gap:10px;align-items:center}
        .progress-wrap{background:#fff;border-bottom:1px solid var(--gray-200);padding:0 16px;overflow-x:auto}.progress-steps{display:flex;min-width:max-content;max-width:840px;margin:0 auto}.step-item{flex:1;display:flex;flex-direction:column;align-items:center;padding:14px 6px;position:relative;min-width:118px}.step-item:not(:last-child)::after{content:"";position:absolute;top:28px;left:calc(50% + 17px);right:calc(-50% + 17px);height:2px;background:var(--gray-200)}.step-item.completed:not(:last-child)::after{background:var(--primary)}.step-circle{width:30px;height:30px;border-radius:50%;border:2px solid var(--gray-400);background:#fff;color:var(--gray-600);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;z-index:1}.step-item.active .step-circle,.step-item.completed .step-circle{border-color:var(--primary);background:var(--primary);color:#fff}.step-label{margin-top:5px;font-size:10px;text-align:center;color:var(--gray-600);line-height:1.3}.step-item.active .step-label{color:var(--primary);font-weight:700}
        .main{max-width:760px;margin:0 auto;padding:24px 16px 108px}.card{background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);padding:26px 24px;margin-bottom:16px}.section-title{font-size:17px;font-weight:800;color:var(--primary);border-bottom:2px solid var(--accent);padding-bottom:9px;margin-bottom:20px}.section-title span{display:block;font-size:12px;font-weight:400;color:var(--gray-600);margin-top:2px}.row{display:grid;gap:14px;margin-bottom:14px}.row-2{grid-template-columns:1fr 1fr}.row-3{grid-template-columns:1fr 1fr .55fr}.row-1{grid-template-columns:1fr}.fg{display:flex;flex-direction:column;gap:5px}.fg.span2{grid-column:1/-1}.lbl{font-size:13px;font-weight:700;color:var(--gray-800)}.lbl .zh{display:block;font-size:11px;font-weight:400;color:var(--gray-600)}.req{color:var(--danger);margin-left:2px}
        input:not([type]),input[type=text],input[type=email],input[type=tel],input[type=search],input[type=file],select,textarea{border:1.5px solid var(--gray-400);border-radius:6px;padding:9px 12px;font:inherit;font-size:14px;color:var(--gray-800);background:#fff;width:100%}input:focus,select:focus,textarea:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(26,58,107,.1)}input[aria-invalid=true],select[aria-invalid=true],textarea[aria-invalid=true]{border-color:var(--danger);background:#fffafa}.hint{font-size:11px;color:var(--gray-600);margin-top:3px}.error{color:var(--danger);font-size:12px}.error-box{background:#fff0ee;border:1px solid #ffc9c4;color:var(--danger);padding:12px 14px;border-radius:8px;margin-bottom:14px}.hidden{display:none!important}
        .upload-area{border:2px dashed var(--gray-400);border-radius:var(--radius);padding:22px 16px;text-align:center;cursor:pointer;background:var(--gray-50);position:relative}.upload-area:hover{border-color:var(--primary);background:rgba(26,58,107,.03)}.upload-area input[type=file]{position:absolute;inset:0;opacity:0;cursor:pointer;height:100%}.upload-icon{font-size:30px;margin-bottom:6px}.upload-text{font-size:13px;color:var(--gray-600)}.upload-text strong{color:var(--primary)}.upload-sub{font-size:11px;color:var(--gray-600);margin-top:3px}.upload-selected{margin-top:8px;font-size:13px;color:var(--success);font-weight:700}
        .notice{background:#f4f7ff;border:1px solid #c9d8f3;border-left:4px solid var(--accent);border-radius:var(--radius);padding:14px 16px;margin-bottom:18px}.notice h4{font-size:13px;font-weight:800;color:var(--trinity-blue-dark);margin:0 0 7px}.notice p,.notice li{font-size:12px;color:#334155;line-height:1.65}.notice ul{padding-left:16px;margin:0}
        .exam-sticky{position:sticky;top:0;z-index:10;background:#fff;border-bottom:1px solid var(--gray-200);padding:10px 0;margin:0 0 16px;display:flex;align-items:center;justify-content:space-between;gap:12px}.sel-badge{display:inline-flex;align-items:center;gap:6px;background:var(--primary);color:#fff;padding:4px 13px;border-radius:20px;font-size:12px;font-weight:800}.price-preview{font-size:14px;font-weight:900;color:var(--primary)}.filters{display:grid;grid-template-columns:1fr 220px;gap:10px;margin-bottom:14px}.cat-title{font-size:13px;font-weight:900;color:var(--primary);margin:18px 0 8px;padding-bottom:5px;border-bottom:1px solid var(--gray-200)}.exam-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px}.exam-cb{display:flex;align-items:flex-start;gap:10px;border:1.5px solid var(--gray-200);border-radius:8px;padding:10px 11px;cursor:pointer;background:#fff;min-height:58px}.exam-cb.checked{border-color:var(--primary);background:rgba(26,58,107,.05)}.exam-cb.disabled{opacity:.58;cursor:not-allowed;background:#f8fafc}.exam-cb input{width:16px;height:16px;min-height:auto;margin-top:2px;accent-color:var(--primary)}.exam-name{font-size:13px;font-weight:800;color:var(--gray-800);line-height:1.35}.exam-sub{font-size:11px;color:var(--gray-600);line-height:1.4}.exam-price-tag{margin-left:auto;font-size:12px;font-weight:900;color:var(--primary);white-space:nowrap}.price-box{background:var(--gray-50);border:1px solid var(--gray-200);border-radius:8px;padding:14px;margin-top:16px}.price-row{display:flex;justify-content:space-between;gap:12px;padding:5px 0;font-size:13px}.price-row.total{border-top:2px solid var(--primary);margin-top:8px;padding-top:10px;color:var(--primary);font-weight:900;font-size:16px}
        .check-line{display:flex;align-items:flex-start;gap:10px;font-size:13px;font-weight:700;line-height:1.5}.check-line input{width:18px;height:18px;min-height:auto;margin-top:1px;accent-color:var(--primary)}.ghost-btn{background:#fff;border:1.5px dashed var(--gray-400);padding:8px 14px;border-radius:6px;cursor:pointer;font-size:12px;color:var(--gray-600);font-family:inherit}.rev-section h3{font-size:14px;color:var(--primary);margin:0 0 8px}.rev-table{width:100%;border-collapse:collapse}.rev-table td{padding:7px 0;border-bottom:1px solid #edf0f5;font-size:13px;vertical-align:top}.rev-table td:first-child{width:38%;color:var(--gray-600);padding-right:12px}.div{border:0;border-top:1px solid var(--gray-200);margin:16px 0}.pay-options{display:grid;gap:10px}.pay-opt{border:1.5px solid var(--gray-200);border-radius:8px;padding:12px;display:flex;gap:10px;cursor:pointer}.pay-opt.selected{border-color:var(--primary);background:rgba(26,58,107,.05)}.pay-opt input{width:18px;height:18px;margin-top:3px;accent-color:var(--primary)}.pay-opt h4{margin:0 0 4px;color:var(--primary);font-size:14px}.pay-opt p{margin:0;color:var(--gray-600);font-size:12px;line-height:1.55}.badge-soon{display:inline-flex;background:#eef3f9;color:var(--primary);border-radius:999px;padding:2px 7px;font-size:10px}.sig-area{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:16px}.sig-box{border:1px solid var(--gray-200);border-radius:8px;padding:14px;background:var(--gray-50)}.sig-box p{font-size:12px;margin:0 0 8px}.sig-line{height:48px;border-bottom:1.5px solid var(--gray-400);margin-bottom:8px}.confirm-wrap{text-align:center;padding:10px 0}.confirm-icon{width:66px;height:66px;border-radius:50%;display:grid;place-items:center;background:#e8f6ef;color:var(--success);font-size:24px;font-weight:900;margin:0 auto 14px}.ref-box{display:inline-block;background:var(--gray-50);border:1px solid var(--gray-200);border-radius:8px;padding:13px 18px;margin:14px 0}.next-steps{text-align:left;background:var(--gray-50);border-radius:8px;padding:14px 18px;margin-top:16px}.next-steps h4{margin:0 0 8px;color:var(--primary)}.next-steps li{font-size:13px;margin-bottom:6px;line-height:1.5}
        .nav-footer{position:fixed;left:0;right:0;bottom:0;background:#fff;border-top:1px solid var(--gray-200);box-shadow:0 -2px 14px rgba(0,0,0,.06);padding:12px 18px;display:flex;align-items:center;justify-content:center;gap:18px;z-index:50}.btn{border:0;border-radius:6px;padding:11px 20px;font:inherit;font-size:14px;font-weight:900;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;justify-content:center}.btn-primary{background:var(--primary);color:#fff}.btn-primary:hover{background:var(--primary-light)}.btn-outline{background:#fff;color:var(--primary);border:1.5px solid var(--gray-400)}.btn-success{background:var(--success);color:#fff}.step-ind{font-size:12px;color:var(--gray-600);min-width:92px;text-align:center}.loading{opacity:.65;pointer-events:none}.toast{position:fixed;left:50%;bottom:82px;transform:translateX(-50%);z-index:80;max-width:min(520px,calc(100vw - 28px));background:#fff;border:1px solid #ffc9c4;border-left:5px solid var(--danger);box-shadow:0 12px 34px rgba(0,0,0,.16);border-radius:8px;padding:12px 16px;color:var(--danger);font-size:13px;font-weight:800;line-height:1.45}.toast.success{border-color:#b9e2ce;border-left-color:var(--success);color:var(--success)}
        @media(max-width:640px){.main{padding-inline:12px}.card{padding:20px 16px}.row-2,.row-3,.filters,.exam-grid,.sig-area{grid-template-columns:1fr}.header{padding-inline:16px}.header-actions{width:100%;justify-content:space-between}.nav-footer{justify-content:space-between;gap:10px}.btn{padding:10px 14px}.step-item{min-width:92px}}
        .main{max-width:900px}
        .form-intro{display:grid;grid-template-columns:minmax(0,1fr) 260px;gap:22px;align-items:center;border-top:4px solid var(--accent);overflow:hidden}
        .form-intro h2{font-size:24px;line-height:1.2;color:var(--primary);margin:10px 0 8px}
        .form-intro p{font-size:14px;line-height:1.65;color:var(--gray-600);margin:0 0 12px}
        .intro-list{margin:0;padding-left:18px;color:var(--gray-800);font-size:13px;line-height:1.65}
        .intro-summary{border:1px solid #dbe6f3;border-radius:12px;background:#f7fbff;padding:16px}
        .intro-summary strong{display:block;color:var(--primary);font-size:28px;line-height:1;margin-bottom:6px}
        .intro-summary span{display:block;color:var(--trinity-blue-dark);font-size:12px;font-weight:900;text-transform:uppercase;letter-spacing:.06em;margin-bottom:10px}
        .intro-summary p{font-size:13px;margin:0;color:var(--gray-800)}
        @media(max-width:640px){.form-intro{grid-template-columns:1fr}}
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
        .header-badge{border-radius:999px;box-shadow:0 8px 18px rgba(36,78,154,.2)}
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
        input:not([type]),input[type=text],input[type=email],input[type=tel],input[type=search],input[type=date],select,textarea{min-height:44px;border:1.5px solid #d8e4f2;border-radius:8px;padding:11px 13px;background:#f3f7fd;color:#1f2a37;box-shadow:inset 0 1px 0 rgba(255,255,255,.72);transition:border-color .15s ease,box-shadow .15s ease,background .15s ease}
        input:not([type]):hover,input[type=text]:hover,input[type=email]:hover,input[type=tel]:hover,input[type=search]:hover,input[type=date]:hover,select:hover,textarea:hover{background:#eef5ff;border-color:#c9d8ea}
        input:focus,select:focus,textarea:focus{background:#fff;border-color:#6f96c9;box-shadow:0 0 0 3px rgba(42,82,152,.13),0 8px 22px rgba(26,58,107,.08)}
        input[aria-invalid=true],select[aria-invalid=true],textarea[aria-invalid=true]{border-color:var(--danger);background:#fff7f6;box-shadow:0 0 0 3px rgba(180,35,24,.08)}
        .hint{font-size:12px}
        .upload-area{border-color:#c9d8ea;border-radius:12px;background:#f3f7fd}
        .upload-area:hover{background:#edf5ff;border-color:#6f96c9}
        .notice{border-radius:12px;background:#f4f7ff}
        .exam-sticky{top:0;border-radius:10px;background:rgba(255,255,255,.95);border:1px solid #dfe8f4;padding:12px 14px;box-shadow:0 10px 24px rgba(26,58,107,.08)}
        .exam-cb,.pay-opt,.sig-box,.price-box,.next-steps{border-radius:12px}
        .exam-cb{border-color:#dbe6f3;background:#f9fbff}
        .exam-cb.checked,.pay-opt.selected{border-color:#6f96c9;background:#eef5ff;box-shadow:0 8px 20px rgba(26,58,107,.08)}
        .rev-section{border:1px solid #dbe6f3;border-radius:12px;background:#f7fbff;padding:16px;margin-bottom:14px;overflow:hidden}
        .rev-section h3{font-size:14px;color:var(--primary);margin:0 0 12px}
        .rev-table{border-collapse:separate;border-spacing:0;width:100%;overflow:hidden;border-radius:10px;border:1px solid #dbe6f3;background:#fff}
        .rev-table tr:nth-child(odd){background:#f1f7ff}
        .rev-table tr:nth-child(even){background:#fbfdff}
        .rev-table td{border-bottom:1px solid #dbe6f3;padding:10px 12px;font-size:13px;vertical-align:top}
        .rev-table tr:last-child td{border-bottom:0}
        .rev-table td:first-child{width:38%;color:#375476;font-weight:800;background:rgba(219,230,243,.42);padding-right:12px}
        .rev-table td:last-child{color:#1f2a37;font-weight:700}
        .btn{border-radius:8px}
        .nav-footer{border-top:1px solid #dbe6f3;box-shadow:0 -12px 34px rgba(26,58,107,.12)}
        .form-intro{border:1px solid #dfe8f4;border-top:0;background:linear-gradient(135deg,#fff,#f3f7fd);grid-template-columns:minmax(0,1fr) 280px}
        .form-intro h2{font-size:28px}
        .intro-summary{box-shadow:0 14px 34px rgba(16,45,82,.1)}
        .sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0}
        .main{max-width:1140px}
        .form-intro{margin-top:18px}
        @media(max-width:640px){.main{padding-top:18px}.header-title{min-width:180px}.brand-copy{display:none}.form-intro{grid-template-columns:1fr}}
        /* Keep the copied Edification shell from being affected by the form's grid/card/button styles. */
        .primary-color{color:var(--trinity-blue)!important}
        .primary-bg,.btn-primary,.media-head.primary-bg,.cs-price.primary-bg{background:var(--trinity-blue)!important;border-color:var(--trinity-blue)!important}
        .btn-primary:hover{background:var(--trinity-blue-dark)!important;border-color:var(--trinity-blue-dark)!important}
        a:focus,a:hover{color:var(--trinity-blue)!important}
        .btn-light:focus,.btn-light:hover{background:var(--trinity-blue)!important;color:#fff!important;border-color:var(--trinity-blue)!important}
        .main-menu nav ul li a:before,.slider-content h3:before{background:var(--trinity-blue)!important}
        .body_overlay{background-color:var(--trinity-blue)!important}
        .section-title-style2 span:before,
        .section-title-style2 span:after{content:""!important;background:var(--trinity-blue)!important;background-image:none!important;width:36px!important;height:2px!important;top:50%!important;transform:translateY(-50%);left:-52px!important;border-radius:99px}
        .section-title-style2 span:after{left:auto!important;right:-52px!important}
        .white-title span:before,
        .white-title span:after{background:var(--trinity-blue-bright)!important}
        .header-top{background:var(--trinity-blue)!important}
        #header .row,footer .row{display:flex;flex-wrap:wrap;margin-right:-15px;margin-left:-15px;margin-bottom:0;gap:0}
        #header .header-bottom{background:rgba(15,18,24,.62)}
        #header .ht-social li{color:#fff;font-size:14px;font-weight:400;letter-spacing:0}
        #header .header-bottom-inner{min-height:104px}
        #header .logo a{display:inline-flex;align-items:center;background:#fff;border-radius:8px;padding:9px 14px;box-shadow:0 12px 28px rgba(0,0,0,.16)}
        #header .logo img{width:230px;max-height:64px;object-fit:contain}
        #header .main-menu{text-align:center}
        #header .main-menu nav ul li a{padding:43px 15px}
        #header .main-menu nav ul li.active>a,#header .main-menu nav ul li>a:hover{color:var(--trinity-blue-bright)!important}
        #header .public-header-actions{display:flex;flex-flow:row nowrap;align-items:center;justify-content:flex-end;gap:10px;width:max-content;margin-left:auto}
        #header .public-header-actions .btn{min-width:142px;white-space:nowrap;padding:15px 18px}
        #header .public-header-actions .btn.btn-round{border-radius:50px!important;line-height:12px}
        #header .public-header-actions .btn-primary{background:var(--trinity-blue)!important;border-color:var(--trinity-blue)!important;color:#fff!important}
        #header .language-switcher{margin:0}
        #header .language-switcher label{display:block;margin:0}
        #header .language-switcher select{height:46px;min-width:112px;border:1px solid rgba(255,255,255,.55);border-radius:50px;background:rgba(255,255,255,.96);color:#252525;padding:0 16px;font-family:"Open Sans",sans-serif;font-size:14px;font-weight:700;text-transform:uppercase}
        .form-top-band{min-height:150px;background:linear-gradient(rgba(11,16,24,.58),rgba(11,16,24,.58)),url('{{ asset('theme/edification/images/bg/slider-bg1.jpg') }}') center/cover no-repeat}
        .progress-wrap{margin-top:0;border-top:0;box-shadow:0 6px 20px rgba(0,0,0,.05)}
        footer .footer-top{padding-top:120px}
        footer .widget p,footer .widget li{color:rgba(255,255,255,.75)}
        footer .widget a{color:rgba(255,255,255,.8)}
        footer .widget-company img{width:178px;max-width:100%;height:auto;padding:0;background:transparent;border-radius:0;filter:brightness(0) invert(1)!important}
        footer .address h6,
        footer .footer-link li i,
        footer span.post-date i{color:var(--trinity-blue-bright)!important}
        footer .primary-color{color:var(--trinity-blue-bright)!important}
        footer .footer-bottom a{color:inherit;text-decoration:underline;text-underline-offset:2px}
        footer .footer-bottom a:hover{color:var(--trinity-blue-bright)}
        .contact-info:before{background:linear-gradient(90deg,var(--trinity-blue-dark),var(--trinity-blue))!important}
        .cnt-addres-single .icon{color:var(--trinity-blue)!important}
        .contact-form form input:focus,
        .contact-form form textarea:focus{border-color:var(--trinity-blue)!important}
        .contact-form form button{background:var(--trinity-blue)!important}
        .slider-area .owl-nav div img{display:none!important}
        .slider-area .owl-nav div:before{font-family:FontAwesome;font-size:22px;color:#fff}
        .slider-area .owl-nav .owl-prev:before{content:"\f104"}
        .slider-area .owl-nav .owl-next:before{content:"\f105"}
        html[lang="en"] .zh{display:none!important}
        @media(max-width:1199px){#header .main-menu nav ul li a{padding:43px 10px}#header .public-header-actions .btn{padding:15px 18px}}
        @media(max-width:991px){#header .header-bottom{background:rgba(15,18,24,.86)}#header .header-bottom-inner{min-height:auto;padding:18px 0}#header .public-header-actions{justify-content:flex-start;margin-top:10px}.slicknav_btn{margin-top:-39px}.form-top-band{min-height:176px}}
        @media(max-width:575px){#header .header-top{display:none}#header .logo img{width:190px}#header .public-header-actions{gap:7px;flex-wrap:nowrap;width:auto;margin-left:0}#header .language-switcher select{height:42px;min-width:100px}#header .public-header-actions .btn{min-width:118px;padding:12px 14px}.form-top-band{min-height:126px}}

        body{font-family:"Open Sans","Microsoft JhengHei","PingFang TC",Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.65;letter-spacing:0;color:#526071;background:#f5f8fc}
        body *::selection{background:rgba(36,78,154,.88);color:#fff;text-shadow:none}
        body *::-moz-selection{background:rgba(36,78,154,.88);color:#fff;text-shadow:none}
        h1,h2,h3,h4,h5,h6,
        .section-title,
        .form-intro h2,
        .intro-summary strong{font-family:"Playfair Display","Microsoft JhengHei","PingFang TC",serif;letter-spacing:0}
        p,li,label,input,select,textarea,button,.btn,.step-label,.hint,.notice{font-family:"Open Sans","Microsoft JhengHei","PingFang TC",Arial,sans-serif;letter-spacing:0}
        #header .main-menu nav ul li a{font-family:"Open Sans",sans-serif;font-size:13px;font-weight:600;letter-spacing:.01em}
        #header .language-switcher select{font-weight:600;letter-spacing:0}
        #header .public-header-actions .btn{font-weight:700;letter-spacing:0}
        .form-top-band{min-height:132px}
        .main{max-width:1080px;padding-top:34px}
        .card{border-radius:8px;border:1px solid #dfe8f2;box-shadow:0 16px 36px rgba(26,58,107,.08);padding:28px 30px;margin-bottom:20px}
        .form-intro{grid-template-columns:minmax(0,1fr) 280px;margin-top:0;background:#fff;border:1px solid #dfe8f2;border-radius:8px;box-shadow:0 18px 40px rgba(26,58,107,.08)}
        .form-intro h2{font-size:30px;line-height:40px;color:#1b3f7b;margin:12px 0 8px}
        .form-intro p{font-size:16px;line-height:28px;color:#647386}
        .intro-list{font-size:14px;line-height:1.7;color:#1f2f44}
        .intro-summary{border-radius:8px;background:#f8fbff;border-color:#cddcee;box-shadow:none}
        .intro-summary span{color:#1b3f7b;font-weight:700;letter-spacing:.08em}
        .intro-summary p{font-size:14px;line-height:24px;color:#34455d}
        .header-badge{background:#e6edf8;color:#1b3f7b;border-radius:50px;font-weight:700;box-shadow:none}
        .section-title{font-size:20px;line-height:30px;font-weight:700;color:#1e2c39;border-bottom:1px solid #dfe8f2;padding-bottom:13px;margin-bottom:24px}
        .section-title::after{width:48px;height:2px;background:var(--trinity-blue);margin-top:11px}
        .section-title span{font-family:"Open Sans","Microsoft JhengHei","PingFang TC",Arial,sans-serif;font-size:13px;font-weight:400;color:#6b7a90}
        .lbl{font-size:13px;font-weight:600;color:#2f4157;text-transform:none;letter-spacing:0}
        .lbl .zh{font-size:12px;font-weight:400;color:#708197}
        input:not([type]),input[type=text],input[type=email],input[type=tel],input[type=search],input[type=date],input[type=file],select,textarea{min-height:45px;border:1px solid #cfdcea;border-radius:6px;background:#fff;color:#253247;font-size:15px;font-weight:400;padding:11px 13px;box-shadow:none}
        input:not([type]):hover,input[type=text]:hover,input[type=email]:hover,input[type=tel]:hover,input[type=search]:hover,input[type=date]:hover,select:hover,textarea:hover{background:#fff;border-color:#9fb6d7}
        input:focus,select:focus,textarea:focus{border-color:var(--trinity-blue);box-shadow:0 0 0 3px rgba(36,78,154,.12)}
        .notice{background:#f8fbff;border-color:#cddcee;border-left-color:var(--trinity-blue);border-radius:8px}
        .notice h4,.pay-opt h4,.next-steps h4,.rev-section h3{font-family:"Playfair Display","Microsoft JhengHei","PingFang TC",serif;font-weight:700;color:#1e2c39}
        .sel-badge,
        .price-preview,
        .cat-title,
        .exam-price-tag,
        .price-row.total,
        .check-line,
        .toast,
        .exam-name{font-weight:700}
        .exam-cb,.pay-opt,.sig-box,.price-box,.next-steps,.rev-section{border-radius:8px}
        .btn{border-radius:50px;font-family:"Open Sans",sans-serif;font-weight:700;letter-spacing:0}
        .nav-footer .btn-primary{min-width:168px;border-radius:50px}
        .nav-footer{box-shadow:0 -10px 28px rgba(26,58,107,.1)}
        @media(max-width:767px){
            .main{padding-top:22px}
            .card{padding:22px 18px}
            .form-intro{grid-template-columns:1fr}
            .form-intro h2{font-size:26px;line-height:34px}
        }
        body.trinity-form{--trinity-ease:cubic-bezier(.22,1,.36,1);--trinity-move:cubic-bezier(.25,1,.5,1);background:radial-gradient(circle at 15% 0%,rgba(36,78,154,.16),transparent 32%),linear-gradient(180deg,#eef4fb 0%,#f8fbff 42%,#eef4fb 100%)}
        body.trinity-form .header-top{background:linear-gradient(90deg,var(--trinity-blue-dark),var(--trinity-blue))!important}
        body.trinity-form #header .header-bottom{background:rgba(10,16,28,.72);backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px)}
        body.trinity-form #header .header-bottom-inner{border-color:rgba(255,255,255,.12)}
        body.trinity-form #header .logo a{background:rgba(255,255,255,.96);border-radius:8px;box-shadow:0 16px 34px rgba(0,0,0,.18);transition:transform 180ms var(--trinity-ease),box-shadow 180ms ease}
        body.trinity-form #header .logo a:hover{transform:translateY(-1px);box-shadow:0 18px 40px rgba(0,0,0,.22)}
        body.trinity-form .form-top-band{min-height:176px;background:linear-gradient(90deg,rgba(6,12,24,.94),rgba(8,18,36,.66)),url('{{ asset('theme/edification/images/bg/slider-bg2.jpg') }}') center/cover no-repeat}
        body.trinity-form .progress-wrap{position:relative;background:rgba(255,255,255,.92);backdrop-filter:blur(16px);border-bottom:1px solid rgba(36,78,154,.14);box-shadow:0 18px 45px rgba(18,43,82,.08)}
        body.trinity-form .progress-wrap:before{content:"";position:absolute;left:0;right:0;top:0;height:1px;background:linear-gradient(90deg,transparent,rgba(36,78,154,.32),transparent)}
        body.trinity-form .progress-steps{max-width:1120px}
        body.trinity-form .step-circle{width:38px;height:38px;border-color:#d8e5f4;background:#fff;color:#60718a;box-shadow:0 8px 18px rgba(18,43,82,.06);transition:transform 160ms var(--trinity-ease),background-color 180ms ease,color 180ms ease,box-shadow 180ms ease}
        body.trinity-form .step-item.active .step-circle,body.trinity-form .step-item.completed .step-circle{background:var(--trinity-blue);color:#fff;box-shadow:0 12px 26px rgba(36,78,154,.26);transform:translateY(-1px)}
        body.trinity-form .step-item:not(:last-child)::after{top:34px;background:#dfe8f4}
        body.trinity-form .step-item.completed:not(:last-child)::after{background:linear-gradient(90deg,var(--trinity-blue),#8fb4ff)}
        body.trinity-form .step-label{font-size:12px;color:#66768d}
        body.trinity-form .step-item.active .step-label{color:#17366f;font-weight:700}
        body.trinity-form .main{max-width:1120px;padding-top:42px}
        body.trinity-form .form-intro{position:relative;overflow:hidden;background:linear-gradient(135deg,#fff 0%,#f8fbff 56%,#edf4ff 100%);border:1px solid rgba(36,78,154,.12);border-top:0;box-shadow:0 26px 70px rgba(18,43,82,.12)}
        body.trinity-form .form-intro:before{content:"";position:absolute;left:0;right:0;top:0;height:4px;background:linear-gradient(90deg,var(--trinity-blue),#8fb4ff)}
        body.trinity-form .form-intro:after{content:"";position:absolute;right:-110px;top:-150px;width:360px;height:360px;border-radius:50%;background:radial-gradient(circle,rgba(36,78,154,.18),transparent 66%);pointer-events:none}
        body.trinity-form .form-intro>*{position:relative;z-index:1}
        body.trinity-form .card{border:1px solid rgba(36,78,154,.12);box-shadow:0 22px 60px rgba(18,43,82,.09);background:rgba(255,255,255,.96);backdrop-filter:blur(10px)}
        body.trinity-form .section-title{font-size:22px;line-height:32px;color:#172033}
        body.trinity-form .section-title::after{background:linear-gradient(90deg,var(--trinity-blue),#8fb4ff)}
        body.trinity-form input:not([type]),body.trinity-form input[type=text],body.trinity-form input[type=email],body.trinity-form input[type=tel],body.trinity-form input[type=search],body.trinity-form input[type=date],body.trinity-form input[type=file],body.trinity-form select,body.trinity-form textarea{background:#f9fbff;border-color:#cddbec;transition:border-color 160ms ease,box-shadow 160ms ease,background-color 160ms ease,transform 140ms var(--trinity-ease)}
        body.trinity-form input:focus,body.trinity-form select:focus,body.trinity-form textarea:focus{background:#fff;border-color:var(--trinity-blue);box-shadow:0 0 0 4px rgba(36,78,154,.12),0 12px 24px rgba(18,43,82,.06)}
        body.trinity-form .exam-cb,body.trinity-form .pay-opt{background:#fff;border:1px solid rgba(36,78,154,.13);box-shadow:0 10px 26px rgba(18,43,82,.045);transition:transform 140ms var(--trinity-ease),border-color 160ms ease,box-shadow 160ms ease,background-color 160ms ease}
        body.trinity-form .exam-cb.checked,body.trinity-form .pay-opt.selected{border-color:rgba(36,78,154,.46);background:#f1f6ff;box-shadow:0 16px 34px rgba(36,78,154,.11)}
        @media(hover:hover) and (pointer:fine){
            body.trinity-form .exam-cb:not(.disabled):hover,body.trinity-form .pay-opt:hover{transform:translateY(-2px);border-color:rgba(36,78,154,.34);box-shadow:0 16px 36px rgba(18,43,82,.09)}
        }
        body.trinity-form .step-enter{animation:form-step-in 260ms var(--trinity-ease) both}
        @keyframes form-step-in{from{opacity:0;transform:translate3d(10px,0,0);filter:blur(3px)}to{opacity:1;transform:translate3d(0,0,0);filter:blur(0)}}
        body.trinity-form .nav-footer{background:rgba(255,255,255,.88);backdrop-filter:blur(16px)}
        body.trinity-form .nav-footer .btn{transition:transform 140ms var(--trinity-ease),background-color 180ms ease,box-shadow 180ms ease}
        body.trinity-form .nav-footer .btn:active{transform:scale(.98)}
        body.trinity-form .nav-footer .btn-primary{box-shadow:0 16px 34px rgba(36,78,154,.24)}
        @media(prefers-reduced-motion:reduce){
            body.trinity-form *,body.trinity-form .step-enter{transition:none!important;animation:none!important;transform:none!important;filter:none!important}
        }

        /* Final form surface: restrained Edification styling without layered blue cards. */
        body.trinity-form{color:#525e6d;background:#f3f5f7;font-family:var(--trinity-body);font-size:15px;line-height:1.6}
        body.trinity-form h1,body.trinity-form h2,body.trinity-form h3,body.trinity-form h4,body.trinity-form h5,body.trinity-form h6{font-family:var(--trinity-display);letter-spacing:0}
        body.trinity-form #header .logo a{padding:0;background:transparent;border-radius:0;box-shadow:none}
        body.trinity-form #header .logo a:hover{transform:none;box-shadow:none}
        body.trinity-form #header .logo img{width:154px;height:auto;max-height:88px;filter:brightness(0) invert(1)}
        body.trinity-form .form-top-band{min-height:116px;background:linear-gradient(rgba(7,13,24,.66),rgba(7,13,24,.66)),url('{{ asset('theme/edification/images/bg/slider-bg2.jpg') }}') center 42%/cover no-repeat}
        body.trinity-form .progress-wrap{position:relative;padding:0 18px;background:#fff;border-top:0;border-bottom:1px solid #dfe3e8;box-shadow:none;backdrop-filter:none}
        body.trinity-form .progress-wrap:before{display:none}
        body.trinity-form .progress-steps{max-width:1040px}
        body.trinity-form .step-item{padding:17px 8px 15px}
        body.trinity-form .step-item:not(:last-child)::after{top:34px;height:1px;background:#d9dee5}
        body.trinity-form .step-item.completed:not(:last-child)::after{background:var(--trinity-blue)}
        body.trinity-form .step-circle{width:34px;height:34px;color:#667386;background:#fff;border:1px solid #bfc7d2;box-shadow:none;font-family:var(--trinity-body);font-size:12px;font-weight:600;transition:background-color 160ms ease,color 160ms ease,border-color 160ms ease}
        body.trinity-form .step-item.active .step-circle,body.trinity-form .step-item.completed .step-circle{color:#fff;background:var(--trinity-blue);border-color:var(--trinity-blue);box-shadow:none;transform:none}
        body.trinity-form .step-label{margin-top:7px;color:#6b7685;font-family:var(--trinity-body);font-size:11px;line-height:16px}
        body.trinity-form .step-item.active .step-label{color:#183a73;font-weight:600}
        body.trinity-form .main{max-width:1080px;padding:34px 18px 104px}
        body.trinity-form .form-intro{display:grid;grid-template-columns:minmax(0,1.3fr) minmax(270px,.7fr);gap:0;margin:0 0 24px;padding:0;overflow:hidden;background:#fff;border:1px solid #dfe3e8;border-top:1px solid #dfe3e8;border-radius:4px;box-shadow:0 8px 22px rgba(24,34,49,.06)}
        body.trinity-form .form-intro:before,body.trinity-form .form-intro:after{display:none}
        body.trinity-form .form-intro>div{padding:34px 36px}
        body.trinity-form .form-intro h2{margin:11px 0 12px;color:#1c2b3f;font-family:var(--trinity-display);font-size:32px;line-height:40px;font-weight:700}
        body.trinity-form .form-intro p{margin:0 0 13px;color:#657181;font-size:15px;line-height:25px}
        body.trinity-form .intro-list{margin:0;padding-left:18px;color:#39485c;font-size:13px;line-height:21px}
        body.trinity-form .header-badge{display:inline-flex;padding:5px 10px;color:#244e9a;background:#edf3fc;border:0;border-radius:50px;box-shadow:none;font-family:var(--trinity-body);font-size:11px;font-weight:600}
        body.trinity-form .intro-summary{display:flex;min-height:260px;flex-direction:column;justify-content:flex-end;padding:28px;color:#fff;background:linear-gradient(rgba(7,13,24,.2),rgba(7,13,24,.78)),url('{{ asset('theme/edification/images/course/cs-img2.jpg') }}') center/cover no-repeat;border:0;border-radius:0;box-shadow:none}
        body.trinity-form .intro-summary span{color:rgba(255,255,255,.76);font-family:var(--trinity-body);font-size:11px;font-weight:600;letter-spacing:0;text-transform:uppercase}
        body.trinity-form .intro-summary strong{margin:6px 0;color:#fff;font-family:var(--trinity-display);font-size:31px;line-height:36px}
        body.trinity-form .intro-summary p{margin:0;color:rgba(255,255,255,.82);font-size:13px;line-height:21px}
        body.trinity-form .card{padding:30px 32px;margin-bottom:18px;background:#fff;border:1px solid #dfe3e8;border-radius:4px;box-shadow:0 7px 18px rgba(24,34,49,.05);backdrop-filter:none}
        body.trinity-form .section-title{margin-bottom:25px;padding:0 0 15px;color:#1d2939;border-bottom:1px solid #dfe3e8;font-family:var(--trinity-display);font-size:23px;line-height:30px;font-weight:700}
        body.trinity-form .section-title::after{display:none}
        body.trinity-form .section-title span{display:block;margin-top:3px;color:#738093;font-family:var(--trinity-body);font-size:12px;font-weight:400;line-height:18px}
        body.trinity-form .row{gap:17px;margin-bottom:17px}
        body.trinity-form .fg{gap:6px}
        body.trinity-form .lbl{color:#344155;font-family:var(--trinity-body);font-size:12px;font-weight:600;letter-spacing:0;text-transform:none}
        body.trinity-form .lbl .zh{margin-top:1px;color:#7c8796;font-size:11px;font-weight:400}
        body.trinity-form input:not([type]),body.trinity-form input[type=text],body.trinity-form input[type=email],body.trinity-form input[type=tel],body.trinity-form input[type=search],body.trinity-form input[type=date],body.trinity-form input[type=file],body.trinity-form select,body.trinity-form textarea{min-height:45px;padding:10px 12px;color:#263244;background:#fff;border:1px solid #c8cfd8;border-radius:3px;box-shadow:none;font-family:var(--trinity-body);font-size:14px;font-weight:400;transition:border-color 150ms ease,box-shadow 150ms ease,background-color 150ms ease}
        body.trinity-form input:not([type]):hover,body.trinity-form input[type=text]:hover,body.trinity-form input[type=email]:hover,body.trinity-form input[type=tel]:hover,body.trinity-form input[type=search]:hover,body.trinity-form input[type=date]:hover,body.trinity-form select:hover,body.trinity-form textarea:hover{background:#fff;border-color:#9ba8b7}
        body.trinity-form input:focus,body.trinity-form select:focus,body.trinity-form textarea:focus{background:#fff;border-color:#5478b7;box-shadow:0 0 0 3px rgba(36,78,154,.1);outline:0}
        body.trinity-form input[aria-invalid=true],body.trinity-form select[aria-invalid=true],body.trinity-form textarea[aria-invalid=true]{background:#fffafa;border-color:var(--danger);box-shadow:0 0 0 3px rgba(180,35,24,.07)}
        body.trinity-form .hint{color:#7a8492;font-size:11px}
        body.trinity-form .upload-area{padding:25px 18px;background:#fafbfc;border:1px dashed #aeb8c5;border-radius:3px}
        body.trinity-form .upload-area:hover{background:#f7f9fc;border-color:#5478b7}
        body.trinity-form .upload-icon{color:var(--trinity-blue);font-size:25px}
        body.trinity-form .notice{padding:15px 18px;margin-bottom:18px;background:#f7f8fa;border:0;border-left:3px solid var(--trinity-blue);border-radius:0}
        body.trinity-form .notice h4{margin-bottom:6px;color:#26364d;font-family:var(--trinity-display);font-size:15px;font-weight:700}
        body.trinity-form .notice p,body.trinity-form .notice li{color:#566274;font-size:12px;line-height:20px}
        body.trinity-form .step-aside-layout{display:grid;grid-template-columns:minmax(0,1fr) 300px;gap:18px;align-items:start}
        body.trinity-form .step-aside-layout>.card{margin-bottom:0}
        body.trinity-form .step-aside-layout>.notice{position:sticky;top:176px;margin:0;padding:22px 24px;background:#fff;border:1px solid #dfe3e8;border-radius:4px;box-shadow:0 7px 18px rgba(24,34,49,.05)}
        body.trinity-form .step-aside-layout>.notice h4{display:flex;align-items:center;gap:10px;margin-bottom:10px;color:#1d2939;font-size:18px;line-height:25px}
        body.trinity-form .step-aside-layout>.notice h4::before{content:"i";display:inline-grid;width:24px;height:24px;flex:0 0 24px;place-items:center;color:#244e9a;border:1px solid #9fb0c8;border-radius:50%;font-family:Georgia,serif;font-size:14px;font-style:italic;font-weight:700}
        body.trinity-form .step-aside-layout>.notice ul{margin:0;padding-left:18px}
        body.trinity-form .step-aside-layout>.notice li+li{margin-top:8px}
        body.trinity-form .exam-sticky{top:0;padding:11px 0;margin-bottom:17px;background:#fff;border:0;border-bottom:1px solid #dfe3e8;border-radius:0;box-shadow:none}
        body.trinity-form .sel-badge{padding:5px 11px;color:#fff;background:var(--trinity-blue);border-radius:50px;font-size:11px;font-weight:600}
        body.trinity-form .price-preview{color:#1d3f79;font-family:var(--trinity-display);font-size:15px;font-weight:700}
        body.trinity-form .cat-title{margin:20px 0 9px;padding-bottom:7px;color:#233f6e;border-bottom:1px solid #dfe3e8;font-family:var(--trinity-body);font-size:12px;font-weight:650}
        body.trinity-form .exam-cb,body.trinity-form .pay-opt{padding:13px;background:#fff;border:1px solid #d9dee5;border-radius:3px;box-shadow:none;transition:border-color 150ms ease,background-color 150ms ease,transform 140ms var(--trinity-ease)}
        body.trinity-form .exam-cb.checked,body.trinity-form .pay-opt.selected{background:#f5f8fd;border-color:#6584ba;box-shadow:none}
        body.trinity-form .exam-name{color:#263244;font-size:13px;font-weight:650}
        body.trinity-form .exam-sub{color:#778292;font-size:11px}
        body.trinity-form .exam-price-tag{color:#244e9a;font-size:11px;font-weight:650}
        body.trinity-form .price-box,body.trinity-form .sig-box,body.trinity-form .next-steps,body.trinity-form .rev-section{background:#f8f9fb;border:1px solid #dfe3e8;border-radius:3px;box-shadow:none}
        body.trinity-form .price-row.total{color:#183a73;border-top:1px solid #aebdce;font-family:var(--trinity-display);font-size:16px;font-weight:700}
        body.trinity-form .pay-opt h4,body.trinity-form .next-steps h4,body.trinity-form .rev-section h3{color:#26364d;font-family:var(--trinity-display);font-weight:700}
        body.trinity-form .rev-table{background:#fff;border:1px solid #dfe3e8;border-radius:0}
        body.trinity-form .rev-table tr:nth-child(odd),body.trinity-form .rev-table tr:nth-child(even){background:#fff}
        body.trinity-form .rev-table td{padding:10px 12px;border-bottom:1px solid #e5e8ed;font-size:12px}
        body.trinity-form .rev-table td:first-child{color:#596677;background:#f6f7f9;font-weight:600}
        body.trinity-form .rev-table td:last-child{color:#263244;font-weight:500}
        body.trinity-form .ghost-btn{color:#3d4b5f;background:#fff;border:1px dashed #aeb8c5;border-radius:3px}
        body.trinity-form .nav-footer{padding:11px 18px;background:#fff;border-top:1px solid #dfe3e8;box-shadow:0 -6px 18px rgba(24,34,49,.07);backdrop-filter:none}
        body.trinity-form .nav-footer .btn{min-width:130px;border-radius:50px;font-family:var(--trinity-body);font-weight:650;box-shadow:none}
        body.trinity-form .nav-footer .btn-primary{min-width:160px;box-shadow:0 8px 18px rgba(36,78,154,.18)}
        body.trinity-form .step-ind{color:#6d7888;font-size:11px}
        body.trinity-form .step-enter{animation:form-step-in 220ms var(--trinity-ease) both}
        @media(hover:hover) and (pointer:fine){body.trinity-form .exam-cb:not(.disabled):hover,body.trinity-form .pay-opt:hover{border-color:#9aa9bb;box-shadow:none;transform:translateY(-1px)}}
        body.trinity-form footer .footer-top .row{display:grid!important;grid-template-columns:minmax(0,4fr) minmax(180px,3fr) minmax(0,5fr)!important;gap:48px!important;margin:0!important}
        body.trinity-form footer .footer-top .row>[class*="col-"]{width:auto!important;max-width:none!important;padding:0!important;flex:none!important}
        body.trinity-form footer .footer-top .widget{margin:0!important}
        @media(max-width:991px){body.trinity-form .form-intro{grid-template-columns:1fr}body.trinity-form .intro-summary{min-height:230px}body.trinity-form .form-top-band{min-height:100px}body.trinity-form .step-aside-layout{grid-template-columns:1fr}body.trinity-form .step-aside-layout>.notice{position:static}body.trinity-form footer .footer-top .row{grid-template-columns:repeat(2,minmax(0,1fr))!important;gap:38px 32px!important}body.trinity-form footer .footer-top .row>:last-child{grid-column:1/-1}}
        @media(max-width:767px){body.trinity-form .main{padding:24px 12px 100px}body.trinity-form .form-intro>div{padding:25px 22px}body.trinity-form .form-intro h2{font-size:27px;line-height:34px}body.trinity-form .card{padding:24px 18px}body.trinity-form .section-title{font-size:20px}body.trinity-form .form-top-band{min-height:86px}}
        @media(max-width:575px){body.trinity-form #header .logo img{width:118px;max-height:68px}body.trinity-form .progress-wrap{padding:0 8px}body.trinity-form .step-item{min-width:94px}body.trinity-form .nav-footer .btn{min-width:0}body.trinity-form footer .footer-top .row{grid-template-columns:1fr!important;gap:32px!important}body.trinity-form footer .footer-top .row>:last-child{grid-column:auto}}
    </style>
</head>
<body class="trinity-form">
<header id="header">
    <div class="header-top">
        <div class="container">
            <div class="row d-flex flex-center">
                <div class="col-sm-8">
                    <div class="ht-address">
                        <ul>
                            <li><i class="fa fa-phone"></i>886-2-2771-6002</li>
                            <li><i class="fa fa-envelope"></i>info@trinityscholar.com</li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="ht-social">
                        <ul>
                            <li>{{ $navLabels['support'] }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-bottom">
        <div class="container">
            <div class="header-bottom-inner">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-sm-8">
                        <div class="logo">
                            <a href="{{ route('landing') }}"><img src="{{ asset($brandLogo) }}" alt="Trinity Scholar"></a>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 d-none d-lg-block">
                        <div class="main-menu">
                            <nav>
                                <ul id="m_menu_active">
                                    <li><a href="{{ route('landing') }}">{{ $navLabels['home'] }}</a></li>
                                    <li><a href="{{ route('landing') }}#overview">{{ $navLabels['program'] }}</a></li>
                                    <li><a href="{{ route('landing') }}#timeline">{{ $navLabels['timeline'] }}</a></li>
                                    <li><a href="{{ route('landing') }}#fees">{{ $navLabels['fees'] }}</a></li>
                                    <li><a href="{{ route('landing') }}#faq">{{ $navLabels['faq'] }}</a></li>
                                    <li><a href="{{ route('landing') }}#contact">{{ $navLabels['contact'] }}</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-sm-4">
                        <div class="public-header-actions">
                            <x-language-switcher />
                            <a class="btn btn-primary btn-round active" href="{{ route('student-registrations.create') }}">{{ $navLabels['start'] }}</a>
                        </div>
                    </div>
                    <div class="col-12 d-block d-lg-none">
                        <div id="mobile_menu"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="form-top-band"></div>
<div class="progress-wrap" aria-label="Registration progress">
    <div class="progress-steps">
        @foreach($stepLabels as $index => $label)
            <div class="step-item {{ $index === 0 ? 'active' : '' }}" data-progress="{{ $index + 1 }}">
                <div class="step-circle">{{ $index + 1 }}</div>
                <div class="step-label">{{ $label }}</div>
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
        <div class="error-box" id="errorSummary"><strong>{{ $tx('Please review the form', '請檢查表單') }}</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <section class="card form-intro" aria-labelledby="registration-intro-title">
        <div>
            <span class="header-badge">{{ $introCopy['badge'] }}</span>
            <h2 id="registration-intro-title">{{ $introCopy['title'] }}</h2>
            <p>{{ $introCopy['body'] }}</p>
            <ul class="intro-list">
                @foreach($introCopy['items'] as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        </div>
        <aside class="intro-summary" aria-label="Late registration summary">
            <span>{{ $introCopy['summary_label'] }}</span>
            <strong>{{ $introCopy['summary_title'] }}</strong>
            <p>{{ $introCopy['summary_body'] }}</p>
        </aside>
    </section>

    <form id="studentForm" method="POST" action="{{ route('student-registrations.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="passport_file_token" id="passportFileToken" value="{{ $passportDraftToken }}">
        <input type="hidden" name="passport_file_name" id="passportFileName" value="{{ $passportDraft['name'] ?? '' }}">
        <section data-step="1">
            <div class="step-aside-layout">
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
                    <div class="fg"><label class="lbl">Student Phone <span class="req">*</span><span class="zh">學生電話</span></label><input type="tel" name="student_phone" value="{{ old('student_phone') }}" placeholder="0912345678" inputmode="numeric" pattern="[0-9]{6,20}" maxlength="20" data-numeric="digits" data-numeric-label="Student Phone" required></div>
                </div>
                <div class="row row-1">
                    <div class="fg">
                        <label class="lbl">Passport Upload <span class="req">*</span><span class="zh">護照上傳（照片頁需清楚）</span></label>
                        <div class="upload-area">
                            <input type="file" name="passport_file" id="passportFile" accept=".pdf,.jpg,.jpeg,.png">
                            <div class="upload-icon"><i class="fa fa-cloud-upload" aria-hidden="true"></i></div>
                            <div class="upload-text"><strong>{{ $tx('Click to upload', '點選上傳') }}</strong> {{ $tx('or drag & drop', '或拖曳檔案') }}</div>
                            <div class="upload-sub">{{ $tx('PDF, JPG, PNG / Max 10MB / clear passport photo page required', 'PDF、JPG、PNG / 最大 10MB / 請上傳清楚的護照照片頁') }}</div>
                            <div class="upload-selected {{ $passportDraft ? '' : 'hidden' }}" id="fileLabel">
                                @if($passportDraft)
                                    {{ $tx('Selected', '已選擇') }}: {{ $passportDraft['name'] }}
                                @endif
                            </div>
                        </div>
                        @if($passportDraft)
                            <span class="hint">{{ $tx('This file is saved temporarily after the validation error. Upload a new file only if you want to replace it.', '此檔案已在驗證錯誤後暫存；只有需要替換時才需重新上傳。') }}</span>
                        @endif
                        @error('passport_file')<span class="error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
            <div class="notice"><h4>{{ $tx('Important Notice', '重要提醒') }}</h4><ul><li>{{ $tx('Except AP Chinese, late or exception exam sessions are not offered.', '除 AP 中文外，不提供補考或例外考試場次。') }}</li><li>{{ $tx('Once payment is submitted, cancelled exams are non-refundable.', '繳費後取消考試恕不退費。') }}</li></ul></div>
            </div>
        </section>

        <section class="hidden" data-step="2">
            <div class="card">
                <div class="section-title">Parent / Guardian Information <span>家長 / 監護人資料</span></div>
                <div class="row row-2"><div class="fg"><label class="lbl">Parent First Name <span class="req">*</span><span class="zh">家長名字</span></label><input name="parent_first_name" value="{{ old('parent_first_name') }}" required></div><div class="fg"><label class="lbl">Parent Last Name <span class="req">*</span><span class="zh">家長姓氏</span></label><input name="parent_last_name" value="{{ old('parent_last_name') }}" required></div></div>
                <div class="row row-1"><div class="fg"><label class="lbl">Relationship to Student <span class="req">*</span><span class="zh">與學生關係</span></label><input name="relationship" value="{{ old('relationship') }}" placeholder="Mother, Father, Guardian" required></div></div>
                <div class="row row-2"><div class="fg"><label class="lbl">Parent Email <span class="req">*</span><span class="zh">家長電子郵件</span></label><input type="email" name="parent_email" value="{{ old('parent_email') }}" required></div><div class="fg"><label class="lbl">Parent Phone <span class="req">*</span><span class="zh">家長電話</span></label><input type="tel" name="parent_phone" value="{{ old('parent_phone') }}" inputmode="numeric" pattern="[0-9]{6,20}" maxlength="20" data-numeric="digits" data-numeric-label="Parent Phone" required></div></div>
                <div class="row row-1"><div class="fg"><label class="lbl">Mailing Address <span class="req">*</span><span class="zh">通訊地址</span></label><input name="mailing_address" value="{{ old('mailing_address') }}" required></div></div>
                <div class="row row-2"><div class="fg"><label class="lbl">City / District <span class="req">*</span><span class="zh">城市 / 區域</span></label><input name="mailing_city" value="{{ old('mailing_city') }}" required></div><div class="fg"><label class="lbl">Postal Code <span class="zh">郵遞區號</span></label><input name="postal_code" value="{{ old('postal_code') }}" inputmode="numeric" pattern="[0-9]{3,12}" maxlength="12" data-numeric="digits" data-numeric-label="Postal Code"></div></div>
            </div>
            <div class="card">
                <div class="section-title">Emergency Contact <span>緊急聯絡人</span></div>
                <div class="row row-2"><div class="fg"><label class="lbl">Name <span class="req">*</span><span class="zh">姓名</span></label><input name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" required></div><div class="fg"><label class="lbl">Phone <span class="req">*</span><span class="zh">電話</span></label><input type="tel" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" inputmode="numeric" pattern="[0-9]{6,20}" maxlength="20" data-numeric="digits" data-numeric-label="Emergency Contact Phone" required></div></div>
                <div class="row row-1"><div class="fg"><label class="lbl">Emergency Contact Relationship <span class="req">*</span><span class="zh">緊急聯絡人關係</span></label><input name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship') }}" placeholder="Father, Mother, Guardian" required></div></div>
            </div>
        </section>

        <section class="hidden" data-step="3">
            <div class="card">
                <div class="section-title">AP Exam Selection <span>AP 考試選擇與費用</span></div>
                <div class="exam-sticky"><span id="selBadge" class="sel-badge">0 selected / 已選 0 科</span><span id="pricePreview" class="price-preview">{{ $tx('Coming Soon', '即將公布') }}</span></div>
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
                                    <div class="exam-sub">{{ $subject->code }} / {{ optional($subject->exam_date)->format('M d, Y') ?? $tx('Date TBA', '日期待公告') }} / {{ __('student_registration.statuses.'.$statusKey) }}</div>
                                    <div class="exam-sub">{{ $tx('Exam Fee', '考試費') }}: {{ $tx('Coming Soon', '即將公布') }} / {{ $tx('Service Fee', '服務費') }}: {{ $tx('Coming Soon', '即將公布') }} / {{ $tx('Late Fee', '逾期費') }}: {{ $tx('Coming Soon', '即將公布') }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @endforeach
                <div class="section-title" style="margin-top:24px">{{ $tx('Practice Exams (Optional)', '模擬考（選填）') }} <span>{{ $tx('Fee: Coming Soon', '費用：即將公布') }}</span></div>
                <div class="notice"><h4>{{ $tx('Practice Exam Info', '模擬考說明') }}</h4><p>{{ $tx('Practice-exam fee information is coming soon. Dates remain subject to change.', '模擬考費用資訊即將公布，日期仍可能調整。') }}</p></div>
                <div class="exam-grid">
                    @php($fallbackPracticeExams = collect(['Biology 生物','English Language and Composition 英文語言與寫作','Physics 1 物理 1','Computer Science A 電腦科學 A','Calculus AB/BC 微積分','Macroeconomics 總體經濟','Precalculus 預備微積分'])->map(fn ($name) => (object) ['uuid' => $name, 'name' => $name, 'fee' => config('registration.practice_exam_fee', 1800), 'currency' => 'NTD', 'practice_date' => null, 'location' => null]))
                    @foreach(($practiceExamOptions ?? collect())->isNotEmpty() ? $practiceExamOptions : $fallbackPracticeExams as $practice)
                        <label class="exam-cb"><input type="checkbox" name="practice_exams[]" value="{{ $practice->uuid }}" data-type="practice" data-p="{{ $practice->fee }}" data-name="{{ $tx('Practice:', '模擬考：') }} {{ $practice->name }}" @checked(in_array($practice->uuid, old('practice_exams', [])))><div><div class="exam-name">{{ $practice->name }}</div><div class="exam-sub">{{ $tx('Practice Exam', '模擬考') }} @if($practice->practice_date) / {{ $practice->practice_date->format('M d, Y') }} @endif @if($practice->location) / {{ $practice->location }} @endif</div></div><div class="exam-price-tag">{{ $tx('Coming Soon', '即將公布') }}</div></label>
                    @endforeach
                </div>
                <input type="hidden" name="practice_exam_total" id="practiceExamTotal" value="0">
                <div class="price-box">
                    <div class="price-row"><span>{{ $tx('Regular AP Exams', '正式考試') }} (<span id="regCt">0</span>)</span><span id="regTot">{{ $tx('Coming Soon', '即將公布') }}</span></div>
                    <div class="price-row"><span>{{ $tx('Practice Exams', '模擬考') }} (<span id="praCt">0</span>)</span><span id="praTot">{{ $tx('Coming Soon', '即將公布') }}</span></div>
                    <div class="price-row"><span>{{ $tx('Late Registration Fee', '逾期報名費') }}</span><span id="lateTot">{{ $tx('Coming Soon', '即將公布') }}</span></div>
                    <div class="price-row total"><span>{{ $tx('Total Due', '應付總額') }}</span><span id="grandTot">{{ $tx('Coming Soon', '即將公布') }}</span></div>
                    <p class="hint">{{ $tx('Final pricing confirmed by AP Coordinator.', '最終費用由 AP 協調員確認。') }}</p>
                </div>
            </div>
        </section>

        <section class="hidden" data-step="4">
            <div class="card">
                <div class="section-title">Testing Accommodations <span>特殊考試需求</span></div>
                <div class="notice"><h4>{{ $tx('About Accommodations', '關於特殊需求') }}</h4><p>{{ $tx('If you qualify for extra time, food/medication, reader/scribe, or other approved accommodations, please contact the AP Coordinator first.', '如需延長時間、藥物或其他核准協助，請先聯繫 AP 協調員。') }}</p></div>
                <label class="check-line" style="margin-bottom:18px"><input type="checkbox" name="needs_accommodations" value="1" id="needsAccom" @checked(old('needs_accommodations'))><span>{{ $tx('I am requesting testing accommodations', '我需要申請特殊考試需求') }}</span></label>
                <div id="accomFields" class="hidden">
                    <div class="row row-2"><div class="fg"><label class="lbl">SSD Code <span class="zh">College Board SSD 代碼</span></label><input name="ssd_code" value="{{ old('ssd_code') }}" placeholder="SSD Code"></div><div class="fg"><label class="lbl">Approval Status <span class="zh">核准狀態</span></label><select name="accommodation_status"><option value="">Select / 請選擇</option><option value="approved" @selected(old('accommodation_status') === 'approved')>Already Approved / 已核准</option><option value="pending" @selected(old('accommodation_status') === 'pending')>Pending / 審核中</option><option value="new" @selected(old('accommodation_status') === 'new')>New Request / 新申請</option></select></div></div>
                    <label class="lbl" style="margin-bottom:8px;display:block">{{ $tx('Exam Name and Requested Accommodation Rows', '考科與申請內容') }}</label>
                    <div id="accomRows">
                        @for($i = 0; $i < $accommodationRows; $i++)
                            <div class="row row-2 accom-row"><div class="fg"><input name="accommodations[{{ $i }}][exam]" value="{{ old("accommodations.$i.exam") }}" placeholder="Exam name / 考科名稱"></div><div class="fg"><input name="accommodations[{{ $i }}][request]" value="{{ old("accommodations.$i.request") }}" placeholder="Accommodation requested / 申請項目"></div></div>
                        @endfor
                    </div>
                    <button type="button" class="ghost-btn" id="addAccomRow">+ {{ $tx('Add row', '新增一列') }}</button>
                </div>
            </div>
            <div class="card">
                <div class="section-title">{{ $tx('AP Preparation Interest', 'AP 備考課程意願') }} <span>{{ $tx('Optional tutoring survey', '選填課程需求調查') }}</span></div>
                <div class="notice"><h4>{{ $tx('Optional tutoring survey', '選填課程需求調查') }}</h4><p>{{ $tx('This does not affect AP exam registration. It helps the team follow up if the student is interested in AP preparation support.', '此調查不影響 AP 考試報名，僅協助團隊了解學生是否需要 AP 備考支援。') }}</p></div>
                <label class="check-line" style="margin-bottom:12px"><input type="checkbox" name="preparation_interest" value="1" id="prepInterest" @checked(old('preparation_interest'))><span>{{ $tx('I am interested in AP preparation / tutoring information.', '我有興趣了解 AP 備考 / 家教資訊。') }}</span></label>
                <div class="row row-2">
                    <label class="check-line"><input type="checkbox" name="group_class_interest" value="1" @checked(old('group_class_interest'))><span>{{ $tx('Group class interest', '團體課程意願') }}</span></label>
                    <label class="check-line"><input type="checkbox" name="private_tutoring_interest" value="1" @checked(old('private_tutoring_interest'))><span>{{ $tx('Private tutoring interest', '一對一家教意願') }}</span></label>
                </div>
                <div class="row row-2">
                    <div class="fg"><label class="lbl">{{ $tx('Preferred Schedule', '偏好上課時間') }}</label><input name="preferred_tutoring_schedule" value="{{ old('preferred_tutoring_schedule') }}" placeholder="{{ $tx('Weekday evening, weekend morning, flexible', '平日晚上、週末上午、時間彈性') }}"></div>
                    <div class="fg"><label class="lbl">{{ $tx('Preferred Language', '偏好語言') }}</label><select name="preferred_tutoring_language"><option value="">{{ $tx('Select', '請選擇') }}</option><option value="English" @selected(old('preferred_tutoring_language') === 'English')>{{ $tx('English', '英文') }}</option><option value="Mandarin" @selected(old('preferred_tutoring_language') === 'Mandarin')>{{ $tx('Mandarin', '中文') }}</option><option value="Bilingual" @selected(old('preferred_tutoring_language') === 'Bilingual')>{{ $tx('Bilingual', '雙語') }}</option></select></div>
                </div>
                <div class="row row-1">
                    <div class="fg"><label class="lbl">{{ $tx('Preparation Notes', '備考需求備註') }}</label><textarea name="preparation_notes" placeholder="{{ $tx('Subjects, goals, availability, or questions', '科目、目標、可上課時間或問題') }}">{{ old('preparation_notes') }}</textarea></div>
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
                <div class="rev-section"><h3>{{ $tx('AP Preparation Interest', 'AP 備考課程意願') }}</h3><div id="rPrep" class="hint" style="margin-top:8px">-</div></div>
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
                <div class="notice" style="margin-top:16px"><h4>{{ $tx('Acknowledgement', '聲明確認') }}</h4><ul><li>{{ $tx('All information provided is accurate and complete.', '所填資料正確且完整。') }}</li><li>{{ $tx('I understand there are no refunds once payment is made.', '繳費後恕不退費。') }}</li><li>{{ $tx('I have verified the exam schedule for conflicts.', '我已確認考試時程無衝突。') }}</li></ul></div>
                <div class="sig-area"><div class="sig-box"><p><strong>{{ $tx('Student Signature', '學生簽名') }}</strong></p><div class="sig-line"></div><p>{{ $tx('Date:', '日期：') }} _____________</p></div><div class="sig-box"><p><strong>{{ $tx('Parent / Guardian Signature', '家長 / 監護人簽名') }}</strong></p><div class="sig-line"></div><p>{{ $tx('Date:', '日期：') }} _____________</p></div></div>
                <div style="margin-top:18px"><label class="check-line"><input type="checkbox" name="confirmed_review" value="1" required @checked(old('confirmed_review'))><span>{{ $tx('I have read and agree to the terms above.', '我已閱讀並同意以上條款。') }} <span class="req">*</span></span></label></div>
                <input type="hidden" name="accurate_information" value="1"><input type="hidden" name="ap_policies" value="1"><input type="hidden" name="privacy_policy" value="1"><input type="hidden" name="terms_conditions" value="1">
            </div>
        </section>

        <section class="hidden" data-step="6">
            <div class="card">
                <div class="confirm-wrap">
                    <div class="confirm-icon">OK</div>
                    <h2>{{ $tx('Registration Submitted!', '報名已送出！') }}</h2>
                    <p>{{ $tx('Your AP Exam registration is ready to submit. After submission, the AP Coordinator will review it and contact you to confirm payment.', '您的 AP 考試報名即將送出。送出後 AP 協調員將審核並聯繫您確認付款。') }}</p>
                    <div class="ref-box">{{ $tx('Reference No.', '參考編號') }}<br><strong>{{ $tx('Generated after submission', '送出後產生') }}</strong></div>
                    <p class="hint">{{ $tx('Confirmation email will be sent to', '確認信將寄至') }} <strong id="confEmail">-</strong></p>
                    <div class="next-steps"><h4>{{ $tx('Next Steps', '後續步驟') }}</h4><ol><li>{{ $tx('AP Coordinator verifies your registration', 'AP 協調員審核報名資料') }}</li><li>{{ $tx('Complete payment by the registration deadline', '在截止日前完成付款') }}</li><li>{{ $tx('Confirm payment with school cashier', '向學校出納確認款項') }}</li><li>{{ $tx('Watch your email for exam schedule details', '留意電子郵件中的考試時程') }}</li></ol></div>
                </div>
            </div>
        </section>
    </form>
</main>
<div class="nav-footer" id="navFooter">
    <button class="btn btn-outline" id="btnBack" type="button" style="visibility:hidden">{{ $isZh ? '上一步' : 'Back' }}</button>
    <span class="step-ind" id="stepInd">{{ $isZh ? '第 1 步，共 6 步' : 'Step 1 of 6' }}</span>
    <button class="btn btn-primary" id="btnNext" type="button">{{ $isZh ? '下一步' : 'Next' }}</button>
</div>
<div class="toast hidden" id="formToast" role="status" aria-live="polite"></div>
<footer>
    <div class="footer-top has-color pt--120 pb--30">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="widget widget-company">
                        <a href="{{ route('landing') }}"><img src="{{ asset($footerLogo) }}" alt="Trinity Scholar"></a>
                        <div class="address"><h6>{{ $footerLabels['office'] }}</h6><p>{{ $footerLabels['office_body'] }}</p></div>
                        <div class="address"><h6>{{ $footerLabels['phone'] }}</h6><p>886-2-2771-6002</p></div>
                        <div class="address"><h6>{{ $footerLabels['email'] }}</h6><p>info@trinityscholar.com</p></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="widget footer-link">
                        <h4 class="fwidget-title mb-5 pb-3 primary-color">{{ $footerLabels['registration'] }}</h4>
                        <ul>
                            <li><a href="{{ route('landing') }}#overview"><i class="fa fa-angle-right"></i>{{ $footerLabels['program'] }}</a></li>
                            <li><a href="{{ route('landing') }}#timeline"><i class="fa fa-angle-right"></i>{{ $footerLabels['timeline'] }}</a></li>
                            <li><a href="{{ route('landing') }}#fees"><i class="fa fa-angle-right"></i>{{ $footerLabels['fees'] }}</a></li>
                            <li><a href="{{ route('student-registrations.create') }}"><i class="fa fa-angle-right"></i>{{ $footerLabels['register'] }}</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="widget widget-opening">
                        <h4 class="fwidget-title mb-5 pb-3 primary-color">{{ $footerLabels['notice'] }}</h4>
                        <p>{{ $footerLabels['notice_body'] }}</p>
                        <ul>
                            <li><span>{{ $footerLabels['main_period'] }}</span>{{ $footerLabels['main_period_value'] }}</li>
                            <li><span>{{ $footerLabels['late_period'] }}</span>{{ $footerLabels['late_period_value'] }}</li>
                            <li><span>{{ $footerLabels['deadline'] }}</span>{{ $footerLabels['deadline_value'] }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>{{ $footerLabels['copyright'] }} &copy; 2026 Trinity Scholar. {{ $footerLabels['rights'] }} {{ $footerLabels['designed'] }} <a href="https://devhouse.sophistec.global/" target="_blank" rel="noopener">Sophistec Dev House</a>. {{ $footerLabels['powered'] }} <a href="https://sophistec.global/" target="_blank" rel="noopener">Sophistec Global</a>.</p>
            </div>
        </div>
    </div>
</footer>
<script src="{{ asset('theme/edification/js/vendor/jquery-2.2.4.min.js') }}"></script>
<script src="{{ asset('theme/edification/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('theme/edification/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('theme/edification/js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('theme/edification/js/jquery.slicknav.min.js') }}"></script>
<script src="{{ asset('theme/edification/js/plugins.js') }}"></script>
<script src="{{ asset('theme/edification/js/scripts.js') }}"></script>
<script>
    document.addEventListener('click', function (event) {
        const link = event.target.closest('a[href*="#"]');
        if (!link) return;

        const url = new URL(link.href, window.location.href);
        if (url.pathname !== window.location.pathname || !url.hash) return;

        const target = document.getElementById(url.hash.slice(1));
        if (!target) return;

        event.preventDefault();
        history.pushState(null, '', url.hash);
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

    const isZhLocale = @json($isZh);
    const uiText = {
        back: isZhLocale ? '上一步' : 'Back',
        next: isZhLocale ? '下一步' : 'Next',
        submit: isZhLocale ? '送出' : 'Submit',
        submitting: isZhLocale ? '送出中' : 'Submitting',
        saving: isZhLocale ? '暫存中' : 'Saving',
        selected: isZhLocale ? '已選擇' : 'Selected',
        selectedCount: count => isZhLocale ? `已選 ${count} 科` : `${count} selected`,
        step: (current, total) => isZhLocale ? `第 ${current} 步，共 ${total} 步` : `Step ${current} of ${total}`,
        examRequired: isZhLocale ? '請至少選擇一科 AP 考試。' : 'Please select at least one AP exam.',
        uploadedFile: isZhLocale ? '已選擇檔案' : 'Uploaded file',
        noAccommodations: isZhLocale ? '未申請特殊考試需求' : 'No accommodations requested',
        requested: isZhLocale ? '已申請' : 'Requested',
        notRequested: isZhLocale ? '未申請' : 'Not requested',
        prepInterest: isZhLocale ? '有 AP 準備課程興趣' : 'Interested in AP preparation',
        groupClass: isZhLocale ? '團體課程' : 'Group class',
        privateTutoring: isZhLocale ? '一對一家教' : 'Private tutoring',
        schedule: isZhLocale ? '可上課時間' : 'Schedule',
        language: isZhLocale ? '偏好語言' : 'Language',
        examFeeLine: (count, amount) => isZhLocale ? `${count} 科 / NT$ ${amount}` : `${count} exams / NT$ ${amount}`,
        comingSoon: isZhLocale ? '即將公布' : 'Coming Soon',
        accomExamPlaceholder: isZhLocale ? '考科名稱' : 'Exam name',
        accomRequestPlaceholder: isZhLocale ? '申請項目' : 'Accommodation requested',
    };

    function localizeStaticFormCopy() {
        const cjk = /[\u3400-\u9fff]/;
        const localizeTextNodes = root => {
            if (!root) return;
            const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, {
                acceptNode(node) {
                    const text = node.nodeValue || '';
                    const parts = text.split(' / ');
                    return parts.length === 2 && !cjk.test(parts[0]) && cjk.test(parts[1]) ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_REJECT;
                }
            });
            const nodes = [];
            while (walker.nextNode()) nodes.push(walker.currentNode);
            nodes.forEach(node => {
                const text = node.nodeValue || '';
                const parts = text.split(' / ');
                if (parts.length !== 2 || cjk.test(parts[0]) || !cjk.test(parts[1])) return;
                node.nodeValue = isZhLocale ? parts[1].trim() : parts[0].trim();
            });
        };

        if (!isZhLocale) {
            document.querySelectorAll('.section-title > span').forEach(span => {
                if (cjk.test(span.textContent || '')) span.remove();
            });
            localizeTextNodes(document.querySelector('main'));
            localizeTextNodes(document.getElementById('navFooter'));
            return;
        }

        document.querySelectorAll('label.lbl').forEach(label => {
            if (label.querySelector('input, select, textarea')) return;
            const zh = label.querySelector('.zh');
            if (!zh) return;
            const required = Boolean(label.querySelector('.req'));
            label.textContent = zh.textContent.trim();
            if (required) {
                const mark = document.createElement('span');
                mark.className = 'req';
                mark.textContent = ' *';
                label.appendChild(mark);
            }
        });

        document.querySelectorAll('.section-title').forEach(title => {
            const zh = title.querySelector('span');
            if (!zh || !zh.textContent.trim()) return;
            title.textContent = zh.textContent.trim();
        });
        localizeTextNodes(document.querySelector('main'));
        localizeTextNodes(document.getElementById('navFooter'));
    }

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
        label.textContent = name ? `${pending ? uiText.saving : uiText.selected}: ${name}` : '';
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
        document.querySelectorAll('[data-step]').forEach(section => {
            section.classList.add('hidden');
            section.classList.remove('step-enter');
        });
        const nextSection = document.querySelector(`[data-step="${next}"]`);
        nextSection.classList.remove('hidden');
        if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            void nextSection.offsetWidth;
            nextSection.classList.add('step-enter');
        }
        cur = next;
        document.querySelectorAll('[data-progress]').forEach(item => {
            const index = Number(item.dataset.progress);
            item.classList.toggle('active', index === cur);
            item.classList.toggle('completed', index < cur);
            item.querySelector('.step-circle').textContent = index < cur ? '✓' : index;
        });
        document.getElementById('btnBack').style.visibility = cur === 1 ? 'hidden' : 'visible';
        document.getElementById('stepInd').textContent = uiText.step(cur, totalSteps);
        document.getElementById('btnNext').textContent = cur === totalSteps ? uiText.submit : uiText.next;
        document.getElementById('btnNext').className = cur === totalSteps ? 'btn btn-success' : 'btn btn-primary';
        if (cur === 5 || cur === 6) buildReview();
        window.scrollTo({top:0, behavior:'smooth'});
    }

    function validateNumericField(input) {
        if (!input.dataset.numeric) return true;

        input.value = input.value.replace(/\D/g, '');
        input.setCustomValidity('');

        if (input.value && !input.checkValidity()) {
            const label = input.dataset.numericLabel || input.name;
            input.setCustomValidity(`${label} must contain numbers only and use the required length.`);
            return false;
        }

        return true;
    }

    function validateStep() {
        const section = document.querySelector(`[data-step="${cur}"]`);
        for (const input of section.querySelectorAll('input, select, textarea')) {
            validateNumericField(input);

            if (!input.checkValidity()) {
                input.reportValidity();
                return false;
            }
        }
        const selectedRegularExams = [...form.querySelectorAll('input[name="exam_subject_uuids[]"]')]
            .filter(input => input.checked && !input.disabled);
        if (cur === 3 && selectedRegularExams.length === 0) {
            notify(uiText.examRequired);
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
        document.getElementById('regTot').textContent = uiText.comingSoon;
        document.getElementById('praTot').textContent = uiText.comingSoon;
        document.getElementById('lateTot').textContent = uiText.comingSoon;
        document.getElementById('grandTot').textContent = uiText.comingSoon;
        document.getElementById('selBadge').textContent = uiText.selectedCount(regCt + praCt);
        document.getElementById('pricePreview').textContent = uiText.comingSoon;
        document.getElementById('practiceExamTotal').value = praTot;
        return {regCt, praCt, regTot, praTot, lateTot, grand};
    }

    function buildReview() {
        const totals = calculate();
        const names = checkedExams().map(input => input.dataset.name);
        const passport = document.getElementById('passportFile').files[0]?.name || document.getElementById('passportFileName').value || preservedPassportName || uiText.uploadedFile;
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
            ? [field('ssd_code'), field('accommodation_status')].filter(Boolean).join(' / ') || uiText.requested
            : uiText.noAccommodations;
        const prepChoices = [];
        if (document.getElementById('prepInterest')?.checked) prepChoices.push(uiText.prepInterest);
        if (form.elements.group_class_interest?.checked) prepChoices.push(uiText.groupClass);
        if (form.elements.private_tutoring_interest?.checked) prepChoices.push(uiText.privateTutoring);
        if (field('preferred_tutoring_schedule')) prepChoices.push(`${uiText.schedule}: ${field('preferred_tutoring_schedule')}`);
        if (field('preferred_tutoring_language')) prepChoices.push(`${uiText.language}: ${field('preferred_tutoring_language')}`);
        document.getElementById('rPrep').textContent = prepChoices.length ? prepChoices.join(' / ') : uiText.notRequested;
        document.getElementById('rReg').textContent = `${totals.regCt} ${isZhLocale ? '科' : 'exams'} / ${uiText.comingSoon}`;
        document.getElementById('rPra').textContent = `${totals.praCt} ${isZhLocale ? '科' : 'exams'} / ${uiText.comingSoon}`;
        document.getElementById('rLate').textContent = uiText.comingSoon;
        document.getElementById('rTot').textContent = uiText.comingSoon;
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
        row.innerHTML = `<div class="fg"><input name="accommodations[${index}][exam]" placeholder="${uiText.accomExamPlaceholder}"></div><div class="fg"><input name="accommodations[${index}][request]" placeholder="${uiText.accomRequestPlaceholder}"></div>`;
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
        btn.textContent = uiText.submitting;
        localStorage.removeItem(draftKey);
        form.requestSubmit();
    });
    document.getElementById('btnBack').addEventListener('click', () => {
        setStep(Math.max(1, cur - 1));
        saveFormDraft();
    });
    form.querySelectorAll('[data-numeric="digits"]').forEach(input => {
        input.addEventListener('input', () => {
            const cursor = input.selectionStart;
            const before = input.value;
            input.value = input.value.replace(/\D/g, '');
            if (before !== input.value && cursor !== null) input.setSelectionRange(Math.max(0, cursor - 1), Math.max(0, cursor - 1));
            input.setCustomValidity('');
        });
    });
    form.addEventListener('input', saveFormDraft);
    form.addEventListener('change', saveFormDraft);
    localizeStaticFormCopy();
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
