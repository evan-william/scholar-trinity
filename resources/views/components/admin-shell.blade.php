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
    <style>
        :root{--navy:#153764;--blue:#25558f;--gold:#c9a84c;--ink:#1f2a37;--muted:#667085;--line:#d9dee8;--soft:#f5f7fb;--white:#fff;--green:#237a4f;--red:#b42318;--amber:#9a6a00;--shadow:0 14px 42px rgba(22,47,83,.11)}
        *{box-sizing:border-box}
        body{margin:0;background:var(--soft);color:var(--ink);font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Arial,sans-serif}
        a{text-decoration:none}
        .shell{min-height:100vh;display:grid;grid-template-columns:260px minmax(0,1fr)}
        .side{background:#102d52;color:#dbe8f8;padding:18px;position:sticky;top:0;height:100vh;overflow:auto}
        .brand{display:flex;align-items:center;gap:12px;color:#fff;font-weight:950;margin-bottom:22px}
        .mark{width:42px;height:42px;border-radius:8px;background:#fff;color:var(--navy);display:grid;place-items:center;font-size:11px;line-height:1.05;text-align:center}
        .nav-group{display:grid;gap:6px}
        .nav-link{display:flex;align-items:center;justify-content:space-between;gap:10px;color:#dbe8f8;border-radius:7px;padding:10px 11px;font-size:14px;font-weight:800}
        .nav-link:hover,.nav-link.active{background:rgba(255,255,255,.12);color:#fff}
        .main{min-width:0}
        .top{background:#fff;border-bottom:1px solid var(--line);padding:18px 24px;display:flex;justify-content:space-between;gap:16px;align-items:center;position:sticky;top:0;z-index:10}
        .top h1{margin:0;color:var(--navy);font-size:26px;line-height:1.1}
        .top p{margin:4px 0 0;color:var(--muted);font-size:13px}
        .top-actions{display:flex;gap:10px;flex-wrap:wrap;justify-content:flex-end}
        .wrap{max-width:1280px;margin:0 auto;padding:22px 18px 48px}
        .card{background:#fff;border:1px solid var(--line);border-radius:8px;padding:18px;box-shadow:0 4px 16px rgba(22,47,83,.05)}
        .card+ .card{margin-top:14px}
        .section-title{display:flex;align-items:flex-end;justify-content:space-between;gap:12px;margin:0 0 14px}
        .section-title h2{margin:0;color:var(--navy);font-size:20px}
        .section-title p{margin:4px 0 0;color:var(--muted);font-size:13px}
        .btn{border:0;border-radius:6px;padding:10px 14px;background:var(--navy);color:#fff;text-decoration:none;font-weight:900;cursor:pointer;font:inherit;display:inline-flex;align-items:center;justify-content:center;min-height:40px}
        .btn.light{background:#fff;color:var(--navy);border:1.5px solid var(--line)}
        .btn.gold{background:var(--gold);color:#382800}
        .filters{display:grid;grid-template-columns:repeat(6,minmax(0,1fr));gap:10px}
        label{display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:850;color:var(--ink);margin-bottom:12px}
        input,select,textarea{min-height:40px;border:1.5px solid #cbd3df;border-radius:6px;padding:8px 10px;font:inherit;background:#fff;color:var(--ink);width:100%}
        textarea{min-height:92px;resize:vertical}
        .metrics{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px}
        .metric strong{display:block;color:var(--navy);font-size:30px;line-height:1.05;margin:7px 0}
        .metric span{color:var(--muted);font-size:12px;line-height:1.45}
        .metric .label{color:var(--navy);font-weight:950;font-size:13px}
        .grid-2{display:grid;grid-template-columns:1.2fr .8fr;gap:14px}
        table{width:100%;border-collapse:collapse}
        th,td{text-align:left;padding:10px 8px;border-bottom:1px solid #edf0f5;font-size:13px;vertical-align:top}
        th{color:var(--navy);font-size:12px;text-transform:uppercase;letter-spacing:.04em}
        .status{display:inline-flex;border-radius:999px;background:#eef3f9;color:var(--navy);padding:4px 8px;font-size:11px;font-weight:900;text-transform:capitalize}
        .bar{height:8px;background:#edf0f5;border-radius:999px;overflow:hidden;margin-top:7px}
        .bar i{display:block;height:100%;background:var(--navy)}
        .chart{display:flex;align-items:end;gap:8px;height:150px;padding-top:12px}
        .chart-bar{flex:1;min-width:10px;background:linear-gradient(180deg,var(--blue),var(--navy));border-radius:6px 6px 0 0;min-height:4px}
        .muted{color:var(--muted)}
        .notice{background:#fff8e1;color:#5a4000;border:1px solid #f0c040;padding:11px 13px;border-radius:8px;margin-bottom:14px}
        .notice.error{background:#fff0ee;color:var(--red);border-color:#ffc9c4}
        form{margin:0}
        @media(max-width:1050px){.shell{grid-template-columns:1fr}.side{position:static;height:auto}.nav-group{grid-template-columns:repeat(2,minmax(0,1fr))}.filters,.metrics,.grid-2{grid-template-columns:1fr 1fr}.top{position:static}}
        @media(max-width:680px){.top{align-items:flex-start;flex-direction:column}.top-actions{justify-content:flex-start}.filters,.metrics,.grid-2,.nav-group{grid-template-columns:1fr}table{display:block;overflow-x:auto}.wrap{padding-inline:12px}}
    </style>
</head>
<body>
<div class="shell">
    <aside class="side">
        <a class="brand" href="{{ route('admin.dashboard') }}">
            <span class="mark">TS<br>AP</span>
            <span>Trinity Admin</span>
        </a>
        <nav class="nav-group" aria-label="Admin navigation">
            @foreach([
                'admin.dashboard' => 'Dashboard',
                'admin.student-registrations.index' => 'Registrations',
                'admin.payments.index' => 'Payments',
                'admin.receipts.index' => 'Receipts',
                'admin.exports.index' => 'Exports',
                'admin.reports.annual' => 'Annual Report',
                'admin.exam-seasons.index' => 'Exam Seasons',
                'admin.ap-exam-subjects.index' => 'AP Subjects',
                'admin.landing.edit' => 'Landing Content',
                'admin.security.audit.index' => 'Audit Log',
            ] as $route => $label)
                <a class="nav-link {{ request()->routeIs($route) ? 'active' : '' }}" href="{{ route($route) }}">{{ $label }}</a>
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
                <a class="btn light" href="{{ route('landing') }}">Public Site</a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button class="btn" type="submit">Logout</button>
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
