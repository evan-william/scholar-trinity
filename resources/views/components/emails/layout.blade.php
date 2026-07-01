<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body{margin:0;background:#f5f7fb;color:#1f2a37;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI","Microsoft JhengHei",Arial,sans-serif}
        .wrap{max-width:680px;margin:0 auto;padding:24px 14px}.card{background:white;border:1px solid #d9dee8;border-radius:8px;overflow:hidden}.head{background:#153764;color:white;padding:18px 22px;font-weight:900}.body{padding:22px;line-height:1.65}.footer{padding:14px 22px;background:#f8fafc;color:#667085;font-size:12px}.meta{width:100%;border-collapse:collapse}.meta td{padding:7px 0;border-bottom:1px solid #edf0f5}.meta td:first-child{color:#667085;width:38%}
    </style>
</head>
<body><div class="wrap"><div class="card"><div class="head">AP Exam Registration</div><div class="body">{{ $slot }}</div><div class="footer">{{ __('ap_registration.email.footer') }}</div></div></div></body>
</html>
