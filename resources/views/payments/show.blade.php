<x-public-flow-shell
    :title="__('payment.title')"
    heading="Payment Instructions"
    subtitle="Transfer the exact amount, keep the payment reference, and upload proof so the admin team can verify the registration."
    :badge="str_replace('_', ' ', $payment->payment_status)"
    body-class="payment-instructions-page"
>
    <div class="payment-return-notice">
        <i class="fa fa-bookmark-o" aria-hidden="true"></i>
        <p><strong>Keep this page easy to find.</strong> Your payment link is also sent to the student email. You can return at any time using registration reference <strong>{{ $registration->registration_number }}</strong>.</p>
    </div>

    <section class="grid-2 payment-grid">
        <div class="card payment-card payment-summary-card">
            <h2>Payment Summary</h2>
            <table class="summary-table">
                <tr><td>Registration</td><td><strong>{{ $registration->registration_number }}</strong></td></tr>
                <tr><td>Student</td><td>{{ $registration->student_full_name }}</td></tr>
                <tr><td>Payment Reference</td><td><strong>{{ $payment->payment_reference }}</strong></td></tr>
                <tr><td>Status</td><td><span class="status {{ $payment->payment_status }}">{{ ucwords(str_replace('_', ' ', $payment->payment_status)) }}</span></td></tr>
                <tr><td>Deadline</td><td>{{ optional($payment->payment_deadline_at)->format('Y-m-d H:i') ?: 'To be confirmed' }}</td></tr>
            </table>
            <div class="actions payment-actions">
                <a class="btn light" href="{{ route('student-registrations.show', $registration->registration_number) }}">
                    <i class="fa fa-file-text-o" aria-hidden="true"></i>
                    <span>View Registration</span>
                </a>
                <a class="btn light" href="{{ route('landing') }}">
                    <i class="fa fa-home" aria-hidden="true"></i>
                    <span>Back to Landing</span>
                </a>
            </div>
        </div>

        <div class="card payment-card amount-card">
            <h2>Amount Breakdown</h2>
            <table class="summary-table">
                <tr><td>AP Exam Fee</td><td>{{ $payment->currency }} {{ number_format($payment->exam_fee_amount) }}</td></tr>
                <tr><td>Service Fee</td><td>{{ $payment->currency }} {{ number_format($payment->service_fee_amount) }}</td></tr>
                <tr><td>Late Registration Fee</td><td>{{ $payment->currency }} {{ number_format($payment->late_fee_amount) }}</td></tr>
                <tr class="total-row"><td>Total Due</td><td class="amount">{{ $payment->currency }} {{ number_format($payment->grand_total) }}</td></tr>
            </table>
            <p class="payment-completion-note"><i class="fa fa-info-circle" aria-hidden="true"></i> Registration is considered complete only after both the filled-out form and payment are received.</p>
        </div>
    </section>

    <section class="grid-2 payment-grid">
        <div class="card payment-card bank-card">
            <h2>Bank Transfer</h2>
            <table class="summary-table">
                <tr><td>Bank</td><td>{{ $setting->bankDisplayName() ?: 'To be configured' }}</td></tr>
                <tr><td>Account Name</td><td>{{ $setting->account_name ?: '-' }}</td></tr>
                <tr class="account-row"><td>Account Number</td><td><strong>{{ $setting->account_number ?: '-' }}</strong></td></tr>
                <tr><td>Transfer Note</td><td>{{ $setting->manual_instruction ?: 'Use the payment reference when transferring.' }}</td></tr>
            </table>
        </div>

        <div class="card payment-card proof-card">
            <h2>Upload Proof</h2>
            @if($payment->proof_file_path)
                <div class="notice success">Payment proof has been uploaded and is waiting for admin verification.</div>
            @endif
            <form method="POST" enctype="multipart/form-data" action="{{ route('payments.proof.upload', $payment) }}">
                @csrf
                <div class="upload-box">
                    <label>Proof of Payment
                        <input type="file" name="proof" accept=".pdf,.jpg,.jpeg,.png" required>
                    </label>
                    <p>Accepted formats: PDF, JPG, PNG. Upload the transfer receipt clearly showing amount and reference.</p>
                </div>
                <div class="actions payment-actions">
                    <button class="btn btn-primary" type="submit">
                        <i class="fa fa-upload" aria-hidden="true"></i>
                        <span>Upload Proof</span>
                    </button>
                </div>
            </form>
        </div>
    </section>

    <section class="card payment-card gateway-card">
        <div class="gateway-copy">
            <span class="payment-card-icon"><i class="fa fa-credit-card" aria-hidden="true"></i></span>
            <div>
                <h2>Taiwan Gateway Option</h2>
                <p>Credit card or ATM payment will use the configured Taiwan provider after ECPay or NewebPay credentials are approved. Until then, this opens the sandbox/provider payload preview.</p>
            </div>
        </div>
        <div class="actions payment-actions">
            <a class="btn btn-primary" href="{{ route('payments.gateway.start', $payment) }}">
                <span>Continue to Gateway</span>
                <i class="fa fa-arrow-right" aria-hidden="true"></i>
            </a>
        </div>
    </section>
</x-public-flow-shell>
