<p>Dear {{ $receipt->buyer_name }},</p>
<p>Your receipt/fapiao has been issued.</p>
<p><strong>Registration:</strong> {{ $receipt->registration->registration_number }}<br>
<strong>Buyer:</strong> {{ $receipt->buyer_name }}<br>
<strong>Receipt Type:</strong> {{ $receipt->receipt_type }}<br>
<strong>Receipt Amount:</strong> {{ $receipt->currency }} {{ number_format($receipt->taxable_receipt_amount) }}<br>
<strong>Receipt Number:</strong> {{ $receipt->receipt_number ?: '-' }}<br>
<strong>Issue Date:</strong> {{ optional($receipt->issued_at)->format('Y-m-d H:i') ?: '-' }}</p>
<p>If any detail looks incorrect, please contact the AP registration team.</p>
