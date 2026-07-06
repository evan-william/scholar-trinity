<x-emails.layout :title="'AP Payment Reminder'">
    <p>Hello {{ $payment->registration->student_full_name }},</p>
    <p>This is a reminder to complete payment for AP Exam registration reference <strong>{{ $payment->payment_reference }}</strong>.</p>
    <table>
        <tr><td>Amount Due</td><td>{{ $payment->currency }} {{ number_format($payment->grand_total) }}</td></tr>
        <tr><td>Deadline</td><td>{{ optional($payment->payment_deadline_at)->format('Y-m-d H:i') ?: 'As instructed by the AP Coordinator' }}</td></tr>
        <tr><td>Status</td><td>{{ str_replace('_', ' ', $payment->payment_status) }}</td></tr>
    </table>
    <p>Please include the payment reference in your transfer note and upload the proof of payment after transfer.</p>
    @if($setting->bank_name)
        <p><strong>Bank:</strong> {{ $setting->bank_name }} {{ $setting->bank_code ? '('.$setting->bank_code.')' : '' }}<br>
        <strong>Account:</strong> {{ $setting->account_name }} / {{ $setting->account_number }}</p>
    @endif
    <p>Thank you.</p>
</x-emails.layout>
