@props([
    'title' => 'Trinity Scholar',
    'eyebrow' => 'AP Exam Registration',
    'heading' => null,
    'subtitle' => null,
    'badge' => null,
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        :root{--navy:#153764;--blue:#25558f;--gold:#c9a84c;--ink:#1f2a37;--muted:#657286;--line:#dbe2ee;--soft:#f5f7fb;--white:#fff;--green:#237a4f;--red:#a93c34;--amber:#9a6a00;--shadow:0 18px 50px rgba(22,47,83,.12)}
        *{box-sizing:border-box}
        body{margin:0;background:var(--soft);color:var(--ink);font-family:-apple-system,BlinkMacSystemFont,"Segoe UI","Microsoft JhengHei","PingFang TC",Arial,sans-serif}
        a{text-decoration:none}
        .top{position:relative;background:#122f57;color:#fff;overflow:hidden}
        .top::before{content:"";position:absolute;inset:0;background:linear-gradient(90deg,rgba(11,31,58,.94),rgba(21,55,100,.84)),url('{{ asset('theme/edification/images/bg/hero-bg.jpg') }}') center/cover no-repeat}
        .nav,.hero,.wrap,.footer-inner{position:relative;max-width:980px;margin:0 auto}
        .nav{display:flex;align-items:center;justify-content:space-between;gap:14px;padding:16px 18px;border-bottom:1px solid rgba(255,255,255,.18)}
        .brand{display:flex;align-items:center;gap:10px;color:#fff;font-weight:950}
        .mark{width:42px;height:42px;border-radius:6px;background:#fff;color:var(--navy);display:grid;place-items:center;font-size:11px;line-height:1.05;text-align:center}
        .nav-actions{display:flex;gap:10px;flex-wrap:wrap;justify-content:flex-end}
        .nav-actions a,.language-switcher select{min-height:38px;border:1px solid rgba(255,255,255,.35);border-radius:6px;background:rgba(255,255,255,.12);color:#fff;padding:8px 10px;font-weight:850}
        .language-switcher select option{color:var(--ink)}
        .language-switcher label{margin:0}
        .sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0}
        .hero{padding:36px 18px 42px}
        .eyebrow{font-size:12px;text-transform:uppercase;letter-spacing:.12em;font-weight:950;color:#f4d982}
        h1{margin:10px 0 10px;font-size:clamp(30px,5vw,48px);line-height:1.05;color:inherit;letter-spacing:0}
        .subtitle{max-width:720px;color:rgba(255,255,255,.86);line-height:1.7;margin:0}
        .badge{display:inline-flex;align-items:center;border-radius:999px;background:#fff3cf;color:#6b4700;font-weight:950;padding:6px 12px;margin-top:18px}
        .wrap{padding:26px 18px 64px}
        .grid-2{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}
        .grid-3{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}
        .card{background:#fff;border:1px solid var(--line);border-radius:8px;box-shadow:0 4px 16px rgba(22,47,83,.05);padding:22px;margin-bottom:16px}
        .card h2{margin:0 0 14px;color:var(--navy);font-size:20px;line-height:1.2}
        .card h3{margin:0 0 8px;color:var(--navy);font-size:16px}
        .card p,.card li{color:var(--muted);font-size:14px;line-height:1.7}
        .summary-table{width:100%;border-collapse:collapse}
        .summary-table td{padding:9px 0;border-bottom:1px solid #edf1f7;vertical-align:top;font-size:14px}
        .summary-table td:first-child{width:38%;color:var(--muted);padding-right:14px}
        .amount{font-size:26px;font-weight:950;color:var(--navy)}
        .status{display:inline-flex;border-radius:999px;background:#eef3f9;color:var(--navy);padding:4px 9px;font-size:12px;font-weight:900;text-transform:capitalize}
        .status.paid,.status.completed,.status.issued,.status.sent{background:#e8f6ef;color:var(--green)}
        .status.failed,.status.rejected,.status.cancelled{background:#fff0ee;color:var(--red)}
        .status.waiting_verification,.status.pending,.status.pending_payment{background:#fff8e1;color:var(--amber)}
        .notice{border-radius:8px;padding:13px 15px;margin-bottom:14px;border:1px solid #f0c040;background:#fff8e1;color:#5a4000;line-height:1.6}
        .notice.success{border-color:#b8e3ca;background:#e8f6ef;color:var(--green)}
        .notice.error{border-color:#ffc9c4;background:#fff0ee;color:var(--red)}
        .actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:16px}
        .btn{display:inline-flex;align-items:center;justify-content:center;min-height:42px;border:0;border-radius:6px;background:var(--navy);color:#fff;text-decoration:none;padding:10px 15px;font-weight:950;cursor:pointer;font:inherit}
        .btn.light{background:#fff;color:var(--navy);border:1.5px solid var(--line)}
        .btn.gold{background:var(--gold);color:#382800}
        label{display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:850;color:var(--ink);margin-bottom:12px}
        input,select,textarea{border:1.5px solid #cbd3df;border-radius:6px;padding:10px 12px;font:inherit;background:#fff;color:var(--ink)}
        input:focus,select:focus,textarea:focus{outline:none;border-color:var(--navy);box-shadow:0 0 0 3px rgba(21,55,100,.1)}
        .upload-box{border:2px dashed #cbd3df;border-radius:8px;padding:18px;background:#f8fafc}
        .steps{margin:0;padding-left:20px}
        .steps li{margin-bottom:7px}
        code{background:#eef3f9;border-radius:4px;padding:2px 5px;color:var(--navy)}
        .footer{background:#0f2c50;color:#dbe8f8;padding:20px 18px}
        .footer-inner{display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;font-size:13px}
        @media(max-width:760px){.grid-2,.grid-3{grid-template-columns:1fr}.nav{align-items:flex-start;flex-direction:column}.nav-actions{justify-content:flex-start}.summary-table td{display:block;width:100%;padding:7px 0}.summary-table td:first-child{width:100%;border-bottom:0;padding-bottom:0}.hero{padding-top:26px}}
    </style>
</head>
<body>
<header class="top">
    <nav class="nav" aria-label="Public navigation">
        <a class="brand" href="{{ route('landing') }}">
            <span class="mark">TS<br>AP</span>
            <span>Trinity Scholar</span>
        </a>
        <div class="nav-actions">
            <x-language-switcher />
            <a href="{{ route('landing') }}">Landing</a>
            <a href="{{ route('student-registrations.create') }}">Register</a>
        </div>
    </nav>
    <section class="hero">
        <div class="eyebrow">{{ $eyebrow }}</div>
        <h1>{{ $heading ?? $title }}</h1>
        @if($subtitle)
            <p class="subtitle">{{ $subtitle }}</p>
        @endif
        @if($badge)
            <span class="badge">{{ $badge }}</span>
        @endif
    </section>
</header>

<main class="wrap">
    @if(session('status'))
        <div class="notice success">{{ session('status') }}</div>
    @endif
    @if($errors->any())
        <div class="notice error">{{ $errors->first() }}</div>
    @endif

    {{ $slot }}
</main>

<footer class="footer">
    <div class="footer-inner">
        <span>Copyright 2026 Trinity Scholar AP Registration Platform</span>
        <span>Form + payment required before registration is complete</span>
    </div>
</footer>
</body>
</html>
