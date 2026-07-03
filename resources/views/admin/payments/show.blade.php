<x-admin-shell
    :title="$payment->payment_reference"
    subtitle="Manual verification, proof review, gateway metadata, and audit trail."
>
    <section class="grid-2">
        <div class="card">
            <div class="section-title"><h2>Payment</h2><a class="btn light" href="{{ route('admin.payments.index') }}">Back</a></div>
            <table>
                <tbody>
                    <tr><td>Status</td><td><span class="status {{ $payment->payment_status }}">{{ str_replace('_', ' ', $payment->payment_status) }}</span></td></tr>
                    <tr><td>Provider</td><td>{{ $payment->provider ?: '-' }}</td></tr>
                    <tr><td>Method</td><td>{{ str_replace('_', ' ', $payment->payment_method ?: '-') }}</td></tr>
                    <tr><td>Exam Fee</td><td>{{ $payment->currency }} {{ number_format($payment->exam_fee_amount) }}</td></tr>
                    <tr><td>Service Fee</td><td>{{ $payment->currency }} {{ number_format($payment->service_fee_amount) }}</td></tr>
                    <tr><td>Late Fee</td><td>{{ $payment->currency }} {{ number_format($payment->late_fee_amount) }}</td></tr>
                    <tr><td>Total</td><td><strong>{{ $payment->currency }} {{ number_format($payment->grand_total) }}</strong></td></tr>
                    <tr><td>Transaction</td><td>{{ $payment->transaction_id ?: '-' }}</td></tr>
                    <tr><td>Gateway Order</td><td>{{ $payment->gateway_order_id ?: '-' }}</td></tr>
                </tbody>
            </table>
        </div>

        <div class="card">
            <div class="section-title"><h2>Registration</h2><a class="btn light" href="{{ route('admin.student-registrations.show', $payment->registration) }}">Open Registration</a></div>
            <table>
                <tbody>
                    <tr><td>Reference</td><td>{{ $payment->registration->registration_number }}</td></tr>
                    <tr><td>Student</td><td>{{ $payment->registration->student_full_name }}</td></tr>
                    <tr><td>Email</td><td>{{ $payment->registration->student_email }}</td></tr>
                    <tr><td>Parent</td><td>{{ $payment->registration->contact?->parent_email ?: '-' }}</td></tr>
                    <tr><td>Exams</td><td>{{ $payment->registration->exams->pluck('name')->join(', ') ?: '-' }}</td></tr>
                </tbody>
            </table>
        </div>
    </section>

    <section class="grid-2" style="margin-top:14px">
        <div class="card">
            <h2>Proof</h2>
            <table>
                <tbody>
                    <tr><td>File</td><td>{{ $payment->proof_original_name ?: '-' }}</td></tr>
                    <tr><td>Uploaded</td><td>{{ optional($payment->proof_uploaded_at)->format('Y-m-d H:i') ?: '-' }}</td></tr>
                </tbody>
            </table>
            @if($payment->proof_file_path)
                <div class="top-actions" style="justify-content:flex-start;margin-top:14px">
                    <a class="btn light" href="{{ route('admin.payments.proof.preview', $payment) }}" target="_blank">Preview</a>
                    <a class="btn light" href="{{ route('admin.payments.proof.download', $payment) }}">Download</a>
                </div>
            @else
                <p class="muted">No proof uploaded yet.</p>
            @endif
        </div>

        <div class="card">
            <h2>Manual Verification</h2>
            <form method="POST" action="{{ route('admin.payments.verify', $payment) }}">
                @csrf
                <label>Action
                    <select name="action">
                        <option value="verify">Verify</option>
                        <option value="reject">Reject</option>
                    </select>
                </label>
                <label>Note
                    <textarea name="note"></textarea>
                </label>
                <label>Rejection Reason
                    <textarea name="rejected_reason"></textarea>
                </label>
                <button class="btn" type="submit">Submit Verification</button>
            </form>
        </div>
    </section>

    <section class="card" style="margin-top:14px">
        <h2>Payment Logs</h2>
        <table>
            <thead><tr><th>Event</th><th>Old</th><th>New</th><th>Time</th></tr></thead>
            <tbody>
                @forelse($payment->logs->sortByDesc('created_at') as $log)
                    <tr><td>{{ $log->event_type }}</td><td>{{ $log->old_status ?: '-' }}</td><td>{{ $log->new_status ?: '-' }}</td><td>{{ $log->created_at->format('Y-m-d H:i') }}</td></tr>
                @empty
                    <tr><td colspan="4" class="muted">No payment logs yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>
</x-admin-shell>
