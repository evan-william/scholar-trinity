<?php

namespace App\Mail;

use App\Models\ReceiptRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReceiptRequestReceivedMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public readonly ReceiptRequest $receipt)
    {
    }

    public function build(): self
    {
        return $this->subject(__('ap_registration.email.receipt_received_subject', ['reference' => $this->receipt->registration->registration_number]))
            ->view('emails.receipt-request-received');
    }
}
