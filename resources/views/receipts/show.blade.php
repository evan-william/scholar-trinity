<x-public-flow-shell
    title="Receipt Request"
    heading="Receipt Request Status"
    subtitle="The admin team can issue or update fapiao details from the receipt dashboard after payment verification."
    :badge="str_replace('_', ' ', $receipt->status)"
>
    <section class="grid-2">
        <div class="card">
            <h2>Receipt Request</h2>
            <table class="summary-table">
                <tr><td>Registration</td><td>{{ $receipt->registration->registration_number }}</td></tr>
                <tr><td>Buyer</td><td>{{ $receipt->buyer_name ?: '-' }}</td></tr>
                <tr><td>Email</td><td>{{ $receipt->buyer_email ?: '-' }}</td></tr>
                <tr><td>Phone</td><td>{{ $receipt->buyer_phone ?: '-' }}</td></tr>
                <tr><td>Type</td><td>{{ str_replace('_', ' ', $receipt->receipt_type) }}</td></tr>
                <tr><td>Status</td><td><span class="status {{ $receipt->status }}">{{ str_replace('_', ' ', $receipt->status) }}</span></td></tr>
            </table>
        </div>

        <div class="card">
            <h2>Amount and Issuance</h2>
            <table class="summary-table">
                <tr><td>Receipt Amount</td><td class="amount">{{ $receipt->currency }} {{ number_format($receipt->taxable_receipt_amount) }}</td></tr>
                <tr><td>Non-receipt Amount</td><td>{{ $receipt->currency }} {{ number_format($receipt->non_receipt_amount) }}</td></tr>
                <tr><td>Receipt Number</td><td>{{ $receipt->receipt_number ?: '-' }}</td></tr>
                <tr><td>Issued At</td><td>{{ optional($receipt->issued_at)->format('Y-m-d H:i') ?: '-' }}</td></tr>
                <tr><td>Sent At</td><td>{{ optional($receipt->sent_at)->format('Y-m-d H:i') ?: '-' }}</td></tr>
            </table>
        </div>
    </section>

    <section class="card">
        <h2>Next Steps</h2>
        <ol class="steps">
            <li>If status is pending, the admin team still needs to review or issue the receipt.</li>
            <li>If details are incorrect, go back to the payment page and resubmit receipt information before issuance.</li>
            <li>Issued receipt/fapiao information will be sent to the buyer email when configured.</li>
        </ol>
        <div class="actions">
            <a class="btn" href="{{ route('payments.show', $receipt->registration->registration_number) }}">Back to Payment</a>
            <a class="btn light" href="{{ route('landing') }}">Back to Landing</a>
        </div>
    </section>
</x-public-flow-shell>
