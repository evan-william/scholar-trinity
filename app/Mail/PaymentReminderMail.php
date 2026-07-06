<?php

namespace App\Mail;

use App\Models\PaymentSetting;
use App\Models\RegistrationPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentReminderMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public RegistrationPayment $payment,
        public PaymentSetting $setting,
    ) {
    }

    public function build(): self
    {
        return $this->subject('AP Payment Reminder '.$this->payment->payment_reference)
            ->view('emails.payment-reminder')
            ->text('emails.payment-reminder-text');
    }
}
