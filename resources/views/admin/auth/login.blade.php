<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('admin_auth.login_title') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root{--navy:#153764;--gold:#c9a84c;--ink:#1f2a37;--muted:#667085;--line:#d9dee8;--soft:#f5f7fb;--green:#237a4f;--red:#b42318;--shadow:0 24px 70px rgba(12,36,68,.22)}
        *{box-sizing:border-box}
        body{margin:0;min-height:100vh;background:#102d52;color:var(--ink);font-family:"Open Sans",Arial,sans-serif;display:grid;place-items:center;padding:24px}
        h1,h2,h3,h4,h5,h6{font-family:"Playfair Display",Georgia,serif}
        body::before{content:"";position:fixed;inset:0;background:linear-gradient(90deg,rgba(11,31,58,.94),rgba(21,55,100,.82)),url('{{ asset('theme/edification/images/bg/hero-bg.jpg') }}') center/cover no-repeat}
        .login-shell{position:relative;width:min(960px,100%);display:grid;grid-template-columns:1fr 430px;background:#fff;border-radius:8px;overflow:hidden;box-shadow:var(--shadow)}
        .panel{background:#102d52;color:#fff;padding:42px 36px;display:flex;flex-direction:column;justify-content:space-between;gap:42px}
        .brand{display:flex;align-items:center;gap:12px;font-weight:950}
        .mark{width:48px;height:48px;border-radius:8px;background:#fff;color:var(--navy);display:grid;place-items:center;font-size:12px;line-height:1.05;text-align:center}
        .panel h1{margin:0 0 12px;font-size:38px;line-height:1.05;color:#fff}
        .panel p{margin:0;color:rgba(255,255,255,.8);line-height:1.7}
        .checks{display:grid;gap:10px;color:#f4d982;font-size:13px;font-weight:850}
        .card{padding:36px 32px;background:#fff}
        .card h2{margin:0 0 6px;color:var(--navy);font-size:26px}
        .muted{color:var(--muted);font-size:13px;margin:0 0 22px;line-height:1.6}
        label{display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:850;margin-bottom:13px;color:var(--ink)}
        input{min-height:44px;border:1.5px solid #cbd3df;border-radius:6px;padding:10px 12px;font:inherit}
        input:focus{outline:none;border-color:var(--navy);box-shadow:0 0 0 3px rgba(21,55,100,.1)}
        .row{display:flex;justify-content:space-between;align-items:center;gap:12px;margin:2px 0 18px;flex-wrap:wrap}
        .check{flex-direction:row;align-items:center;font-weight:650;margin:0;color:var(--muted)}
        .check input{min-height:auto;width:16px;height:16px}
        .btn{width:100%;border:0;border-radius:6px;padding:12px 16px;background:var(--navy);color:white;font-weight:950;font:inherit;cursor:pointer}
        .error{background:#fff0ee;color:var(--red);padding:10px 12px;border-radius:8px;margin-bottom:12px}
        .status{background:#e8f6ef;color:var(--green);padding:10px 12px;border-radius:8px;margin-bottom:12px}
        a{color:var(--navy);font-weight:850;text-decoration:none}
        @media(max-width:820px){.login-shell{grid-template-columns:1fr}.panel{padding:28px}.panel h1{font-size:30px}.card{padding:28px 22px}}
    </style>
</head>
<body>
<main class="login-shell">
    <section class="panel">
        <div class="brand"><span class="mark">TS<br>AP</span><span>Trinity Scholar Admin</span></div>
        <div>
            <h1>AP Registration Operations</h1>
            <p>Secure dashboard for reviewing registrations, passports, payments, receipt/fapiao requests, exports, and annual exam setup.</p>
        </div>
        <div class="checks">
            <span>Admin session timeout</span>
            <span>Payment and document verification</span>
            <span>Audit trail for sensitive actions</span>
        </div>
    </section>

    <section class="card">
        <h2>{{ __('admin_auth.login_title') }}</h2>
        <p class="muted">Use your admin account to continue.</p>
        @if(session('status'))<div class="status">{{ session('status') }}</div>@endif
        @if($errors->any())<div class="error">{{ $errors->first() }}</div>@endif
        <form method="POST" action="{{ route('admin.login.store') }}">
            @csrf
            <label>{{ __('admin_auth.login_identifier') }}
                <input name="login" type="text" value="{{ old('login') }}" required autofocus autocomplete="username">
            </label>
            <label>{{ __('admin_auth.password') }}
                <input name="password" type="password" required autocomplete="current-password">
            </label>
            <div class="row">
                <label class="check"><input type="checkbox" name="remember" value="1"> {{ __('admin_auth.remember') }}</label>
                <a href="{{ route('admin.password.request') }}">{{ __('admin_auth.forgot_password') }}</a>
            </div>
            <button class="btn" type="submit">{{ __('admin_auth.login') }}</button>
        </form>
    </section>
</main>
</body>
</html>
