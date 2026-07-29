@props([
    'title' => 'Admin',
    'subtitle' => null,
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} | Trinity Scholar Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/trinity-scholar-favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root{--navy:#153764;--blue:#25558f;--ink:#1f2a37;--muted:#667085;--line:#d9dee8;--soft:#f5f7fb;--white:#fff;--green:#237a4f;--red:#b42318;--amber:#9a6a00;--shadow:0 8px 24px rgba(22,47,83,.07)}
        *{box-sizing:border-box}
        body{margin:0;background:var(--soft);color:var(--ink);font-family:"Open Sans",Arial,sans-serif}
        h1,h2,h3,h4,h5,h6{font-family:"Open Sans",Arial,sans-serif;letter-spacing:0}
        a{text-decoration:none}
        .shell{min-height:100vh;display:grid;grid-template-columns:232px minmax(0,1fr)}
        .side{background:#102d52;color:#dbe8f8;padding:18px 14px 26px;position:sticky;top:0;height:100vh;overflow:auto}
        .brand{display:flex;align-items:center;gap:11px;color:#fff;margin:0 4px 23px;padding:0 4px 18px;border-bottom:1px solid rgba(255,255,255,.12)}
        .brand-logo{display:block;width:72px;height:44px;object-fit:contain;filter:brightness(0) invert(1)}
        .brand-label{display:block;color:#fff;font-size:12px;font-weight:700;line-height:1.35}
        .nav-section+.nav-section{margin-top:19px}
        .nav-heading{margin:0 9px 7px;color:#8098b7;font-size:10px;font-weight:700;letter-spacing:.09em;text-transform:uppercase}
        .nav-group{display:grid;gap:3px}
        .nav-link{display:flex;align-items:center;gap:10px;min-height:36px;color:#cbd8e8;border-left:3px solid transparent;border-radius:4px;padding:8px 10px;font-size:12px;font-weight:600}
        .nav-link:hover{background:rgba(255,255,255,.07);color:#fff}
        .nav-link.active{background:rgba(143,180,255,.15);border-left-color:#8fb4ff;color:#fff}
        .main{min-width:0}
        .top{background:rgba(255,255,255,.97);border-bottom:1px solid var(--line);padding:15px clamp(18px,2vw,30px);display:flex;justify-content:space-between;gap:20px;align-items:center;position:sticky;top:0;z-index:10}
        .top h1{margin:0;color:var(--navy);font-size:22px;line-height:1.2;font-weight:700}
        .top p{margin:4px 0 0;color:var(--muted);font-size:13px}
        .top-actions{display:flex;gap:8px;flex-wrap:nowrap;align-items:center;justify-content:flex-end}
        .top-actions .language-switcher,.top-actions .language-switcher label,.top-actions form{margin:0}
        .top-actions select{width:auto;min-width:106px;min-height:36px;padding:6px 28px 6px 9px;font-size:12px}
        .top-actions .btn{min-height:36px;padding:8px 12px;font-size:12px}
        .wrap{width:100%;max-width:1560px;margin:0 auto;padding:22px clamp(16px,2vw,30px) 48px}
        .card{background:#fff;border:1px solid var(--line);border-radius:6px;padding:18px;box-shadow:var(--shadow)}
        .card+ .card{margin-top:14px}
        .section-title{display:flex;align-items:flex-end;justify-content:space-between;gap:12px;margin:0 0 14px}
        .section-title h2{margin:0;color:var(--navy);font-size:17px;font-weight:700}
        .section-title p{margin:4px 0 0;color:var(--muted);font-size:13px}
        .btn{border:1px solid var(--navy);border-radius:4px;padding:8px 12px;background:var(--navy);color:#fff;text-decoration:none;font-family:"Open Sans",Arial,sans-serif;font-size:12px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;min-height:36px}
        .btn.light{background:#fff;color:var(--navy);border-color:#cbd4e1}
        .filters{display:grid;grid-template-columns:repeat(6,minmax(0,1fr));gap:10px}
        label{display:flex;flex-direction:column;gap:6px;font-size:12px;font-weight:700;color:var(--ink);margin-bottom:12px}
        input,select,textarea{min-height:40px;border:1.5px solid #cbd3df;border-radius:6px;padding:8px 10px;font:inherit;background:#fff;color:var(--ink);width:100%}
        textarea{min-height:92px;resize:vertical}
        .metrics{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px}
        .metric strong{display:block;color:var(--navy);font-size:30px;line-height:1.05;margin:7px 0}
        .metric span{color:var(--muted);font-size:12px;line-height:1.45}
        .metric .label{color:var(--navy);font-weight:950;font-size:13px}
        .grid-2{display:grid;grid-template-columns:1.2fr .8fr;gap:14px}
        .grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}
        .grid-3{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}
        .actions{display:flex;gap:8px;flex-wrap:wrap;align-items:center}
        .btn.danger{background:var(--red);color:#fff}
        .action-menu{position:relative;display:inline-flex;justify-content:flex-end}
        .action-menu summary{display:grid;width:34px;height:34px;place-items:center;border:1px solid #cbd4e1;border-radius:4px;background:#fff;color:var(--navy);font-size:18px;font-weight:900;line-height:1;cursor:pointer;list-style:none}
        .action-menu summary::-webkit-details-marker{display:none}
        .action-menu[open] summary{background:#eef3f9;border-color:#b9c7db}
        .action-menu-panel{position:absolute;right:0;top:calc(100% + 6px);z-index:20;display:grid;gap:4px;min-width:164px;padding:6px;background:#fff;border:1px solid var(--line);border-radius:6px;box-shadow:0 16px 34px rgba(15,36,64,.16)}
        .action-menu-panel .btn,.action-menu-panel button{width:100%;min-height:34px;justify-content:flex-start;border:0;border-radius:4px;background:#fff;color:var(--ink);box-shadow:none;text-align:left}
        .action-menu-panel .btn:hover,.action-menu-panel button:hover{background:#eef3f9;color:var(--navy)}
        .action-menu-panel .btn.danger,.action-menu-panel button.danger{color:var(--red);background:#fff}
        .action-menu-panel .btn.danger:hover,.action-menu-panel button.danger:hover{background:#fff0ee}
        .action-menu-panel form{width:100%}
        .action-menu-panel input{margin-bottom:6px}
        .mini{font-size:12px;color:var(--muted)}
        .list{margin:0;padding-left:18px}
        .list li{margin-bottom:6px}
        .timeline{border-left:3px solid var(--line);padding-left:14px}
        .timeline div{margin-bottom:12px}
        .note{background:#fbfcfe;border:1px solid #edf0f5;border-radius:8px;padding:12px;margin-bottom:10px}
        .row-card{padding:14px;border:1px solid #edf0f5;border-radius:8px;background:#fbfcfe;margin-bottom:10px}
        .hint{color:var(--muted);font-size:12px;font-weight:400;line-height:1.5}
        .form-inline{display:inline}
        .compact-input{min-height:34px;padding:6px 8px}
        table{width:100%;border-collapse:collapse}
        th,td{text-align:left;padding:10px 8px;border-bottom:1px solid #edf0f5;font-size:13px;vertical-align:top}
        th{color:var(--navy);font-size:12px;text-transform:uppercase;letter-spacing:.04em}
        .status{display:inline-flex;border-radius:999px;background:#eef3f9;color:var(--navy);padding:4px 8px;font-size:11px;font-weight:900;text-transform:capitalize}
        .bar{height:8px;background:#edf0f5;border-radius:999px;overflow:hidden;margin-top:7px}
        .bar i{display:block;height:100%;background:var(--navy)}
        .chart{display:flex;align-items:end;gap:8px;height:150px;padding-top:12px}
        .chart-bar{flex:1;min-width:10px;background:linear-gradient(180deg,var(--blue),var(--navy));border-radius:6px 6px 0 0;min-height:4px}
        .muted{color:var(--muted)}
        .notice{background:#f3f7fd;color:var(--navy);border:1px solid #cdd9eb;padding:11px 13px;border-radius:6px;margin-bottom:14px}
        .notice.error{background:#fff0ee;color:var(--red);border-color:#ffc9c4}
        form{margin:0}
        pre{white-space:pre-wrap;background:#f8fafc;border:1px solid #edf0f5;border-radius:8px;padding:14px;overflow:auto}
        @media(max-width:1050px){.shell{grid-template-columns:1fr}.side{position:static;height:auto}.brand{max-width:230px}.nav-section{margin-top:12px!important}.nav-group{grid-template-columns:repeat(3,minmax(0,1fr))}.filters,.metrics,.grid-2,.grid,.grid-3{grid-template-columns:1fr 1fr}.top{position:static}}
        @media(max-width:680px){.top{align-items:flex-start;flex-direction:column}.top-actions{justify-content:flex-start}.filters,.metrics,.grid-2,.grid,.grid-3,.nav-group{grid-template-columns:1fr}table{display:block;overflow-x:auto}.wrap{padding-inline:12px}}
    </style>
    {{ $styles ?? '' }}
</head>
<body>
<div class="shell">
    <aside class="side">
        <a class="brand" href="{{ route('admin.dashboard') }}">
            <img class="brand-logo" src="{{ asset('images/trinity-scholar-logo-clean.png') }}" alt="Trinity Scholar">
            <span class="brand-label">{{ __('admin.app_name') }}</span>
        </a>
        @php
            $navSections = [
                'Overview' => [
                    ['route' => 'admin.dashboard', 'active' => 'admin.dashboard', 'label' => __('admin.dashboard')],
                    ['route' => 'admin.notifications.index', 'active' => 'admin.notifications.*', 'label' => 'Notifications'],
                ],
                'Registration' => [
                    ['route' => 'admin.student-registrations.index', 'active' => 'admin.student-registrations.*', 'label' => __('admin.registrations')],
                    ['route' => 'admin.payments.index', 'active' => 'admin.payments.*', 'label' => __('admin.payments')],
                    ['route' => 'admin.receipts.index', 'active' => 'admin.receipts.*', 'label' => __('admin.receipts')],
                    ['route' => 'admin.exports.index', 'active' => 'admin.exports.*', 'label' => __('admin.exports')],
                    ['route' => 'admin.reports.annual', 'active' => 'admin.reports.*', 'label' => __('admin.annual_report')],
                ],
                'Programs' => [
                    ['route' => 'admin.exam-seasons.index', 'active' => 'admin.exam-seasons.*', 'label' => __('admin.exam_seasons')],
                    ['route' => 'admin.ap-exam-subjects.index', 'active' => 'admin.ap-exam-subjects.*', 'label' => __('admin.ap_subjects')],
                    ['route' => 'admin.practice-exams.index', 'active' => 'admin.practice-exams.*', 'label' => 'Practice Exams'],
                ],
                'Content & System' => [
                    ['route' => 'admin.landing.edit', 'active' => 'admin.landing.*', 'label' => __('admin.landing_content')],
                    ['route' => 'admin.email-templates.index', 'active' => 'admin.email-templates.*', 'label' => 'Email Templates'],
                    ['route' => 'admin.system-settings.index', 'active' => 'admin.system-settings.*', 'label' => 'System Settings'],
                    ['route' => 'admin.security.audit.index', 'active' => 'admin.security.audit.*', 'label' => __('admin.audit_log')],
                ],
            ];
        @endphp
        <nav aria-label="Admin navigation">
            @foreach($navSections as $section => $items)
                <div class="nav-section">
                    <p class="nav-heading">{{ $section }}</p>
                    <div class="nav-group">
                        @foreach($items as $item)
                            <a class="nav-link {{ request()->routeIs($item['active']) ? 'active' : '' }}" href="{{ route($item['route']) }}">{{ $item['label'] }}</a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </nav>
    </aside>

    <section class="main">
        <header class="top">
            <div>
                <h1>{{ $title }}</h1>
                @if($subtitle)<p>{{ $subtitle }}</p>@endif
            </div>
            <div class="top-actions">
                <x-language-switcher />
                <a class="btn light" href="{{ route('landing') }}">{{ __('admin.public_site') }}</a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button class="btn" type="submit">{{ __('admin.logout') }}</button>
                </form>
            </div>
        </header>

        <main class="wrap">
            @if(session('status'))
                <div class="notice">{{ session('status') }}</div>
            @endif
            @if($errors->any())
                <div class="notice error">{{ $errors->first() }}</div>
            @endif
            {{ $slot }}
        </main>
    </section>
</div>
</body>
</html>
