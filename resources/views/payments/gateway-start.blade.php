<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gateway Payment</title>
    <style>
        body{margin:0;background:#f5f7fb;color:#1f2a37;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Arial,sans-serif}.wrap{max-width:820px;margin:0 auto;padding:28px 16px}.card{background:white;border:1px solid #d9dee8;border-radius:8px;padding:22px}.btn{display:inline-flex;background:#153764;color:white;border:0;text-decoration:none;padding:11px 16px;border-radius:6px;font-weight:900;cursor:pointer}.btn.light{background:white;color:#153764;border:1.5px solid #d9dee8}table{width:100%;border-collapse:collapse}td{padding:8px;border-bottom:1px solid #edf0f5;font-size:13px;vertical-align:top}.notice{background:#fff8e1;border:1px solid #f0c040;border-radius:8px;padding:12px;margin-bottom:16px}
    </style>
</head>
<body>
<main class="wrap">
    <div class="card">
        <h1>Taiwan Gateway Checkout</h1>

        @if($gatewayActionUrl)
            <p>This payment is ready to submit to the configured Taiwan gateway endpoint.</p>
            <form method="POST" action="{{ $gatewayActionUrl }}">
                @foreach($payload as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <button class="btn" type="submit">Continue to Gateway</button>
                <a class="btn light" href="{{ route('payments.show', $payment->registration->registration_number) }}">Back to Payment</a>
            </form>
        @else
            <div class="notice">
                <strong>Sandbox payload preview.</strong><br>
                Set <code>PAYMENT_GATEWAY_ENDPOINT</code> in the production `.env` after ECPay or NewebPay is chosen and verified.
            </div>
            <table>
                @foreach($payload as $key => $value)
                    <tr><td>{{ $key }}</td><td>{{ $value }}</td></tr>
                @endforeach
            </table>
            <p><a class="btn light" href="{{ route('payments.show', $payment->registration->registration_number) }}">Back to Payment</a></p>
        @endif
    </div>
</main>
</body>
</html>
