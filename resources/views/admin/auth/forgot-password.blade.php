<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <style>
        body{margin:0;min-height:100vh;display:grid;place-items:center;background:#f5f7fb;font-family:"Open Sans",Arial,sans-serif}
        .card{width:min(430px,calc(100vw - 28px));background:white;border:1px solid #d9dee8;border-radius:8px;padding:28px}
        h1{color:#153764;font-family:"Playfair Display",Georgia,serif}
        label{display:flex;flex-direction:column;gap:6px;font-weight:700}
        input{min-height:42px;border:1.5px solid #cbd3df;border-radius:6px;padding:9px 11px;font:inherit}
        .btn{margin-top:14px;width:100%;border:0;border-radius:6px;padding:12px 16px;background:#153764;color:white;font-family:"Open Sans",Arial,sans-serif;font-weight:700}
        .status{background:#e8f6ef;color:#237a4f;padding:10px 12px;border-radius:8px;margin-bottom:12px}
        .error{background:#fff0ee;color:#b42318;padding:10px 12px;border-radius:8px;margin-bottom:12px}
    </style>
</head>
<body>
<main class="card">
    <h1>Reset Password</h1>
    @if(session('status'))<div class="status">{{ session('status') }}</div>@endif
    @if($errors->any())<div class="error">{{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('admin.password.email') }}">
        @csrf
        <label>Email<input name="email" type="email" required></label>
        <button class="btn" type="submit">Send Reset Link</button>
    </form>
</main>
</body>
</html>
