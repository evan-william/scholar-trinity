<x-public-flow-shell
    title="Taiwan Gateway Checkout"
    heading="Taiwan Gateway Checkout"
    subtitle="This page prepares the payment handoff for the selected Taiwan payment provider."
    :badge="$gatewayActionUrl ? 'Ready to submit' : 'Sandbox preview'"
>
    <section class="grid-2">
        <div class="card">
            <h2>Checkout Summary</h2>
            <table class="summary-table">
                <tr><td>Registration</td><td>{{ $payment->registration->registration_number }}</td></tr>
                <tr><td>Student</td><td>{{ $payment->registration->student_full_name }}</td></tr>
                <tr><td>Payment Reference</td><td>{{ $payment->payment_reference }}</td></tr>
                <tr><td>Method</td><td>{{ str_replace('_', ' ', $payment->payment_method ?: 'manual') }}</td></tr>
                <tr><td>Total</td><td class="amount">{{ $payment->currency }} {{ number_format($payment->grand_total) }}</td></tr>
            </table>
        </div>

        <div class="card">
            <h2>Provider Status</h2>
            @if($gatewayActionUrl)
                <div class="notice success">Gateway endpoint is configured. Submit when ready to continue to the provider checkout page.</div>
                <form method="POST" action="{{ $gatewayActionUrl }}">
                    @foreach($payload as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <div class="actions">
                        <button class="btn" type="submit">Continue to Gateway</button>
                        <a class="btn light" href="{{ route('payments.show', $payment->registration->registration_number) }}">Back to Payment</a>
                    </div>
                </form>
            @else
                <div class="notice">
                    <strong>Sandbox payload preview.</strong><br>
                    Set <code>PAYMENT_GATEWAY_ENDPOINT</code> after ECPay or NewebPay is chosen and verified. Real signature/webhook validation is still required before production.
                </div>
                <div class="actions">
                    <a class="btn light" href="{{ route('payments.show', $payment->registration->registration_number) }}">Back to Payment</a>
                </div>
            @endif
        </div>
    </section>

    @unless($gatewayActionUrl)
        <section class="card">
            <h2>Gateway Payload</h2>
            <table class="summary-table">
                @foreach($payload as $key => $value)
                    <tr><td>{{ $key }}</td><td>{{ $value }}</td></tr>
                @endforeach
            </table>
        </section>
    @endunless
</x-public-flow-shell>
