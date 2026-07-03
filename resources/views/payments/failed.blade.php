<x-public-flow-shell
    :title="__('payment.failed')"
    heading="Payment Could Not Be Confirmed"
    subtitle="The registration is still saved. Retry the gateway flow or use manual bank transfer."
    badge="Action required"
>
    <section class="card">
        <div class="notice error">{{ $payment->rejected_reason ?: 'The payment could not be confirmed.' }}</div>
        <table class="summary-table">
            <tr><td>Registration</td><td><strong>{{ $payment->registration->registration_number }}</strong></td></tr>
            <tr><td>Student</td><td>{{ $payment->registration->student_full_name }}</td></tr>
            <tr><td>Payment Reference</td><td>{{ $payment->payment_reference }}</td></tr>
            <tr><td>Total Due</td><td class="amount">{{ $payment->currency }} {{ number_format($payment->grand_total) }}</td></tr>
            <tr><td>Status</td><td><span class="status {{ $payment->payment_status }}">{{ str_replace('_', ' ', $payment->payment_status) }}</span></td></tr>
        </table>
        <div class="actions">
            <a class="btn" href="{{ route('payments.gateway.start', $payment) }}">Retry Gateway</a>
            <a class="btn light" href="{{ route('payments.show', $payment->registration->registration_number) }}">Manual Payment Option</a>
            <a class="btn light" href="{{ route('landing') }}">Back to Landing</a>
        </div>
    </section>
</x-public-flow-shell>
