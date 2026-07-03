<x-admin-shell
    title="Payment Records"
    subtitle="Review manual proof uploads, gateway records, payment status, and transaction references."
>
    <section class="card">
        <div class="section-title">
            <div>
                <h2>Filters</h2>
                <p>Search by reference, registration number, or student name.</p>
            </div>
            <a class="btn light" href="{{ route('admin.payments.settings') }}">Payment Settings</a>
        </div>
        <form class="filters" method="GET" style="grid-template-columns:1.5fr repeat(3,1fr) auto">
            <input name="search" value="{{ request('search') }}" placeholder="Reference, student, registration">
            <select name="payment_status">
                <option value="">All statuses</option>
                @foreach(['pending','proof_uploaded','waiting_verification','paid','failed','cancelled','expired','refunded','rejected'] as $status)
                    <option value="{{ $status }}" @selected(request('payment_status') === $status)>{{ str_replace('_', ' ', $status) }}</option>
                @endforeach
            </select>
            <select name="payment_method">
                <option value="">All methods</option>
                @foreach(['manual_bank_transfer','credit_card','atm','cvs','barcode','apple_pay'] as $method)
                    <option value="{{ $method }}" @selected(request('payment_method') === $method)>{{ str_replace('_', ' ', $method) }}</option>
                @endforeach
            </select>
            <select name="period">
                <option value="">All periods</option>
                <option value="main" @selected(request('period') === 'main')>Main</option>
                <option value="late" @selected(request('period') === 'late')>Late</option>
            </select>
            <button class="btn" type="submit">Filter</button>
        </form>
    </section>

    <section class="card">
        <div class="section-title">
            <div>
                <h2>Payments</h2>
                <p>{{ $payments->total() }} payment record(s)</p>
            </div>
        </div>
        <table>
            <thead>
                <tr><th>Reference</th><th>Registration</th><th>Student</th><th>Method</th><th>Status</th><th>Amount</th><th>Updated</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr>
                        <td><strong>{{ $payment->payment_reference }}</strong></td>
                        <td>{{ $payment->registration?->registration_number }}</td>
                        <td>{{ $payment->registration?->student_full_name }}</td>
                        <td>{{ str_replace('_', ' ', $payment->payment_method ?: '-') }}</td>
                        <td><span class="status {{ $payment->payment_status }}">{{ str_replace('_', ' ', $payment->payment_status) }}</span></td>
                        <td>{{ $payment->currency }} {{ number_format($payment->grand_total) }}</td>
                        <td>{{ $payment->updated_at->format('Y-m-d H:i') }}</td>
                        <td><a class="btn light" href="{{ route('admin.payments.show', $payment) }}">View</a></td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="muted">No payment records found.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $payments->links() }}
    </section>
</x-admin-shell>
