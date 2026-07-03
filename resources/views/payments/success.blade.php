<x-public-flow-shell
    :title="__('payment.success')"
    heading="Payment Successful"
    subtitle="The payment record has been marked paid. Submit receipt/fapiao details for the taxable service fee if needed."
    badge="Paid"
>
    <section class="grid-2">
        <div class="card">
            <h2>Payment Record</h2>
            <table class="summary-table">
                <tr><td>Registration</td><td>{{ $payment->registration->registration_number }}</td></tr>
                <tr><td>Student</td><td>{{ $payment->registration->student_full_name }}</td></tr>
                <tr><td>Exams</td><td>{{ $payment->registration->exams->pluck('name')->join(', ') ?: '-' }}</td></tr>
                <tr><td>Total Paid</td><td class="amount">{{ $payment->currency }} {{ number_format($payment->grand_total) }}</td></tr>
                <tr><td>Method</td><td>{{ str_replace('_', ' ', $payment->payment_method ?: 'manual') }}</td></tr>
                <tr><td>Transaction ID</td><td>{{ $payment->transaction_id ?: '-' }}</td></tr>
                <tr><td>Payment Date</td><td>{{ optional($payment->paid_at)->format('Y-m-d H:i') ?: '-' }}</td></tr>
            </table>
        </div>

        <div class="card">
            <h2>Next Steps</h2>
            <ol class="steps">
                <li>Submit receipt/fapiao information if the family needs a receipt for the service fee.</li>
                <li>Wait for the admin team to verify payment and complete registration.</li>
                <li>Watch student and parent email for final exam coordinator confirmation.</li>
            </ol>
            <div class="actions">
                <a class="btn" href="{{ route('receipts.create', $payment) }}">Submit Receipt Info</a>
                <a class="btn light" href="{{ route('student-registrations.show', $payment->registration->registration_number) }}">View Registration</a>
            </div>
        </div>
    </section>
</x-public-flow-shell>
