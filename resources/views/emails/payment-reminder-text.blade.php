AP Payment Reminder

Hello {{ $payment->registration->student_full_name }},

This is a reminder to complete payment for AP Exam registration reference {{ $payment->payment_reference }}.

Amount Due: {{ $payment->currency }} {{ number_format($payment->grand_total) }}
Deadline: {{ optional($payment->payment_deadline_at)->format('Y-m-d H:i') ?: 'As instructed by the AP Coordinator' }}
Status: {{ str_replace('_', ' ', $payment->payment_status) }}

Please include the payment reference in your transfer note and upload the proof of payment after transfer.

@if($setting->bank_name)
Bank: {{ $setting->bank_name }} {{ $setting->bank_code ? '('.$setting->bank_code.')' : '' }}
Account: {{ $setting->account_name }} / {{ $setting->account_number }}
@endif
