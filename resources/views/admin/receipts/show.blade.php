<x-admin-shell
    :title="$receipt->registration->registration_number.' Receipt'"
    subtitle="Review fee separation, buyer details, manual issue actions, sandbox auto issue, and receipt audit history."
>
    <section class="grid-2">
        <div class="card">
            <div class="section-title"><h2>Receipt</h2><a class="btn light" href="{{ route('admin.receipts.index') }}">Back</a></div>
            <table>
                <tbody>
                    <tr><td>Status</td><td><span class="status {{ $receipt->status }}">{{ str_replace('_', ' ', $receipt->status) }}</span></td></tr>
                    <tr><td>Type</td><td>{{ str_replace('_', ' ', $receipt->receipt_type) }}</td></tr>
                    <tr><td>Buyer</td><td>{{ $receipt->buyer_name ?: '-' }}</td></tr>
                    <tr><td>Email</td><td>{{ $receipt->buyer_email ?: '-' }}</td></tr>
                    <tr><td>Phone</td><td>{{ $receipt->buyer_phone ?: '-' }}</td></tr>
                    <tr><td>Company</td><td>{{ $receipt->company_name ?: '-' }}</td></tr>
                    <tr><td>GUI / Tax ID</td><td>{{ $receipt->gui_tax_id ?: '-' }}</td></tr>
                    <tr><td>Receipt Number</td><td>{{ $receipt->receipt_number ?: '-' }}</td></tr>
                    <tr><td>Issued</td><td>{{ optional($receipt->issued_at)->format('Y-m-d H:i') ?: '-' }}</td></tr>
                </tbody>
            </table>
        </div>

        <div class="card">
            <h2>Fee Separation</h2>
            <table>
                <tbody>
                    <tr><td>Exam Fee (No Receipt)</td><td>{{ $receipt->currency }} {{ number_format($receipt->exam_fee_amount) }}</td></tr>
                    <tr><td>Service Fee</td><td>{{ $receipt->currency }} {{ number_format($receipt->service_fee_amount) }}</td></tr>
                    <tr><td>Late Fee</td><td>{{ $receipt->currency }} {{ number_format($receipt->late_fee_amount) }}</td></tr>
                    <tr><td>Taxable Receipt Amount</td><td><strong>{{ $receipt->currency }} {{ number_format($receipt->taxable_receipt_amount) }}</strong></td></tr>
                    <tr><td>Non-receipt Amount</td><td>{{ $receipt->currency }} {{ number_format($receipt->non_receipt_amount) }}</td></tr>
                    <tr><td>Payment Status</td><td><span class="status {{ $receipt->payment?->payment_status }}">{{ str_replace('_', ' ', $receipt->payment?->payment_status ?: '-') }}</span></td></tr>
                </tbody>
            </table>
        </div>
    </section>

    <section class="grid-2" style="margin-top:14px">
        <div class="card">
            <h2>Edit Information</h2>
            <form method="POST" action="{{ route('admin.receipts.update', $receipt) }}">
                @csrf
                @method('PATCH')
                <label>Receipt Type
                    <select name="receipt_type">
                        <option value="personal" @selected($receipt->receipt_type === 'personal')>Personal</option>
                        <option value="company" @selected($receipt->receipt_type === 'company')>Company</option>
                        <option value="none" @selected($receipt->receipt_type === 'none')>None</option>
                        <option value="donation" @selected($receipt->receipt_type === 'donation')>Donation</option>
                    </select>
                </label>
                <label>Buyer Name<input name="buyer_name" value="{{ old('buyer_name', $receipt->buyer_name) }}"></label>
                <label>Email<input type="email" name="buyer_email" value="{{ old('buyer_email', $receipt->buyer_email) }}"></label>
                <label>Phone<input name="buyer_phone" value="{{ old('buyer_phone', $receipt->buyer_phone) }}"></label>
                <label>Company<input name="company_name" value="{{ old('company_name', $receipt->company_name) }}"></label>
                <label>GUI / Tax ID<input name="gui_tax_id" value="{{ old('gui_tax_id', $receipt->gui_tax_id) }}"></label>
                <label>Notes<textarea name="notes">{{ old('notes', $receipt->notes) }}</textarea></label>
                <button class="btn" type="submit">Save Receipt Info</button>
            </form>
        </div>

        <div class="card">
            <h2>Actions</h2>
            <form method="POST" action="{{ route('admin.receipts.issue', $receipt) }}">
                @csrf
                <label>Receipt Number<input name="receipt_number" value="{{ old('receipt_number', $receipt->receipt_number) }}"></label>
                <label>Notes<textarea name="notes"></textarea></label>
                <button class="btn" type="submit">Mark Issued</button>
            </form>
            <hr style="border:0;border-top:1px solid var(--line);margin:16px 0">
            <form method="POST" action="{{ route('admin.receipts.status', $receipt) }}">
                @csrf
                <label>Status
                    <select name="status">
                        @foreach(['requested','pending_issue','issued','sent','failed','cancelled','voided'] as $status)
                            <option value="{{ $status }}" @selected($receipt->status === $status)>{{ str_replace('_', ' ', $status) }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Notes<textarea name="notes"></textarea></label>
                <button class="btn light" type="submit">Update Status</button>
            </form>
            <div class="top-actions" style="justify-content:flex-start;margin-top:14px">
                <form method="POST" action="{{ route('admin.receipts.send', $receipt) }}">@csrf<button class="btn light" type="submit">Resend Receipt Email</button></form>
                <form method="POST" action="{{ route('admin.receipts.auto-issue', $receipt) }}">@csrf<button class="btn light" type="submit">Sandbox Auto Issue</button></form>
            </div>
        </div>
    </section>

    <section class="grid-2" style="margin-top:14px">
        <div class="card">
            <h2>Receipt History</h2>
            <table>
                <thead><tr><th>Event</th><th>Old</th><th>New</th><th>Time</th></tr></thead>
                <tbody>
                    @forelse($receipt->logs->sortByDesc('created_at') as $log)
                        <tr><td>{{ $log->event_type }}</td><td>{{ $log->old_status ?: '-' }}</td><td>{{ $log->new_status ?: '-' }}</td><td>{{ $log->created_at->format('Y-m-d H:i') }}</td></tr>
                    @empty
                        <tr><td colspan="4" class="muted">No receipt history yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card">
            <h2>E-Invoice Transactions</h2>
            <table>
                <thead><tr><th>Provider</th><th>Status</th><th>Invoice</th><th>Error</th></tr></thead>
                <tbody>
                    @forelse($receipt->transactions as $transaction)
                        <tr><td>{{ $transaction->provider }}</td><td>{{ $transaction->provider_status }}</td><td>{{ $transaction->provider_invoice_number ?: '-' }}</td><td>{{ $transaction->error_message ?: '-' }}</td></tr>
                    @empty
                        <tr><td colspan="4" class="muted">No e-invoice transactions yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-admin-shell>
