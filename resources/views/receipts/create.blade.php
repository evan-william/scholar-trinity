<x-public-flow-shell
    :title="__('receipt.title')"
    heading="Receipt / Fapiao Information"
    subtitle="Receipt or fapiao data applies only to the taxable service fee. AP exam fees are excluded from receipt issuance."
    badge="Service fee only"
>
    <section class="grid-2">
        <div class="card">
            <h2>Receipt Amount</h2>
            <p>{{ __('receipt.service_fee_only') }}</p>
            <table class="summary-table">
                <tr><td>Registration</td><td>{{ $payment->registration->registration_number }}</td></tr>
                <tr><td>AP Exam Fee</td><td>{{ $payment->currency }} {{ number_format($payment->exam_fee_amount) }} <span class="status">No receipt</span></td></tr>
                <tr><td>Service Fee Receipt Amount</td><td class="amount">{{ $payment->currency }} {{ number_format($taxableAmount) }}</td></tr>
                <tr><td>Non-receipt Amount</td><td>{{ $payment->currency }} {{ number_format($nonReceiptAmount) }}</td></tr>
                <tr><td>Payment Status</td><td><span class="status {{ $payment->payment_status }}">{{ str_replace('_', ' ', $payment->payment_status) }}</span></td></tr>
            </table>
        </div>

        <div class="card">
            <h2>Receipt Rules</h2>
            <ol class="steps">
                <li>Personal receipt needs buyer name, email, and phone.</li>
                <li>Company receipt needs company name and GUI / Tax ID.</li>
                <li>Fapiao is issued after payment is verified by admin.</li>
                <li>Choose no receipt required if the family does not need one.</li>
            </ol>
        </div>
    </section>

    <form class="card" method="POST" action="{{ route('receipts.store', $payment) }}">
        @csrf
        <h2>Buyer Information</h2>
        <div class="grid-2">
            <label>Receipt Type
                <select name="receipt_type" required>
                    <option value="personal" @selected(old('receipt_type', $receipt?->receipt_type) === 'personal')>Personal receipt</option>
                    <option value="company" @selected(old('receipt_type', $receipt?->receipt_type) === 'company')>Company receipt</option>
                    <option value="none" @selected(old('receipt_type', $receipt?->receipt_type) === 'none')>No receipt required</option>
                    <option value="donation" @selected(old('receipt_type', $receipt?->receipt_type) === 'donation')>Donation receipt (future)</option>
                </select>
            </label>
            <label>Buyer Name
                <input name="buyer_name" value="{{ old('buyer_name', $receipt?->buyer_name ?: $payment->registration->contact?->parent_full_name) }}">
            </label>
            <label>Email
                <input type="email" name="buyer_email" value="{{ old('buyer_email', $receipt?->buyer_email ?: $payment->registration->contact?->parent_email) }}">
            </label>
            <label>Phone
                <input name="buyer_phone" value="{{ old('buyer_phone', $receipt?->buyer_phone ?: $payment->registration->contact?->parent_phone) }}">
            </label>
            <label>Company Name
                <input name="company_name" value="{{ old('company_name', $receipt?->company_name) }}">
            </label>
            <label>GUI / Tax ID
                <input name="gui_tax_id" value="{{ old('gui_tax_id', $receipt?->gui_tax_id) }}" maxlength="8" inputmode="numeric">
            </label>
        </div>
        <div class="actions">
            <button class="btn" type="submit">Save Receipt Information</button>
            <a class="btn light" href="{{ route('payments.show', $payment->registration->registration_number) }}">Back to Payment</a>
        </div>
    </form>
</x-public-flow-shell>
