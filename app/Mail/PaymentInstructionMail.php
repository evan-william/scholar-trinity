<?php

namespace App\Mail;

use App\Models\PaymentSetting;
use App\Models\RegistrationPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentInstructionMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly RegistrationPayment $payment,
        public readonly PaymentSetting $setting
    ) {
    }

    public function build(): self
    {
        return $this
            ->subject(__('ap_registration.email.payment_instruction_subject', ['reference' => $this->payment->registration->registration_number]))
            ->view('emails.payment-instruction')
            ->text('emails.payment-instruction-text');
    }
}
