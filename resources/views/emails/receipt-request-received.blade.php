<p>Dear {{ $receipt->buyer_name }},</p>
<p>We have received your receipt/fapiao information.</p>
<p><strong>Registration:</strong> {{ $receipt->registration->registration_number }}<br>
<strong>Receipt Type:</strong> {{ $receipt->receipt_type }}<br>
<strong>Receipt Amount:</strong> {{ $receipt->currency }} {{ number_format($receipt->taxable_receipt_amount) }}</p>
<p>Receipt/fapiao applies only to the Trinity service fee. AP exam fees are excluded.</p>
