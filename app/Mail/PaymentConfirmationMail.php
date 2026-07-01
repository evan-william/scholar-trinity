<?php

namespace App\Mail;

use App\Models\RegistrationPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmationMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public readonly RegistrationPayment $payment)
    {
    }

    public function build(): self
    {
        return $this
            ->subject(__('ap_registration.email.payment_subject', ['reference' => $this->payment->registration->registration_number]))
            ->view('emails.payment-confirmation')
            ->text('emails.payment-confirmation-text');
    }
}
