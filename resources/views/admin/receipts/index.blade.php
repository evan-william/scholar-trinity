<x-admin-shell
    title="Receipt / Fapiao Management"
    subtitle="Track receipt requests, service-fee taxable amounts, issuance status, and e-invoice readiness."
>
    <section class="card">
        <div class="section-title">
            <div>
                <h2>Filters</h2>
                <p>Find tracking records by registration, buyer, invoice number, receipt/fapiao number, type, period, or payment status.</p>
            </div>
            <div class="top-actions">
                <a class="btn light" href="{{ route('admin.receipts.export', request()->query()) }}">Export CSV</a>
                <a class="btn light" href="{{ route('admin.receipts.settings') }}">E-Invoice Settings</a>
            </div>
        </div>
        <form class="filters" method="GET" style="grid-template-columns:1.4fr repeat(5,1fr) auto">
            <input name="search" value="{{ request('search') }}" placeholder="Registration, buyer, invoice or receipt number">
            <select name="status">
                <option value="">All statuses</option>
                @foreach(['not_requested','requested','pending_issue','issued','sent','failed','cancelled','voided'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ str($status)->replace('_', ' ')->title() }}</option>
                @endforeach
            </select>
            <select name="receipt_type">
                <option value="">All types</option>
                @foreach(['personal','company','donation','none'] as $type)
                    <option value="{{ $type }}" @selected(request('receipt_type') === $type)>{{ $type }}</option>
                @endforeach
            </select>
            <select name="period">
                <option value="">All periods</option>
                <option value="main" @selected(request('period') === 'main')>Main</option>
                <option value="late" @selected(request('period') === 'late')>Late</option>
            </select>
            <select name="payment_status">
                <option value="">All payments</option>
                <option value="paid" @selected(request('payment_status') === 'paid')>Paid</option>
                <option value="waiting_verification" @selected(request('payment_status') === 'waiting_verification')>Waiting</option>
                <option value="pending" @selected(request('payment_status') === 'pending')>Pending</option>
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}">
            <button class="btn" type="submit">Filter</button>
        </form>
    </section>

    <section class="card">
        <div class="section-title">
            <div>
                <h2>Receipt Requests</h2>
                <p>{{ $receipts->total() }} registration receipt tracking record(s)</p>
            </div>
        </div>
        <table data-datatable="true" data-table-label="Receipt requests">
            <thead>
                <tr><th>Registration</th><th>Student</th><th>Buyer</th><th>Type</th><th>Company / GUI</th><th>Email</th><th>Status</th><th>Invoice No.</th><th>Receipt / Fapiao No.</th><th>Tracking</th><th data-sortable="false">Actions</th></tr>
            </thead>
            <tbody>
                @forelse($receipts as $receipt)
                    <tr>
                        <td>{{ $receipt->registration?->registration_number }}</td>
                        <td>{{ $receipt->registration?->student_full_name }}</td>
                        <td>{{ $receipt->buyer_name ?: '-' }}</td>
                        <td>{{ str($receipt->receipt_type)->replace('_', ' ')->title() }}</td>
                        <td>{{ $receipt->company_name ?: '-' }}<br><span class="muted">{{ $receipt->gui_tax_id ?: '-' }}</span></td>
                        <td>{{ $receipt->buyer_email ?: '-' }}<br><span class="muted">{{ $receipt->buyer_phone ?: '-' }}</span></td>
                        <td><span class="status {{ $receipt->status }}">{{ str($receipt->status)->replace('_', ' ')->title() }}</span></td>
                        <td>{{ $receipt->invoice_number ?: '-' }}</td>
                        <td>{{ $receipt->receipt_number ?: '-' }}</td>
                        <td>
                            <span class="status {{ $receipt->invoice_received ? 'received' : 'pending' }}">{{ $receipt->invoice_received ? 'Invoice received' : 'Invoice pending' }}</span><br>
                            <span class="status {{ $receipt->receipt_received ? 'received' : 'pending' }}" style="margin-top:4px">{{ $receipt->receipt_received ? 'Fapiao received' : 'Fapiao pending' }}</span>
                        </td>
                        <td>
                            <x-admin-actions>
                                <a class="btn light" href="{{ route('admin.receipts.show', $receipt) }}">View</a>
                            </x-admin-actions>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="11" class="muted">No receipt tracking records found.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $receipts->links() }}
    </section>
</x-admin-shell>
