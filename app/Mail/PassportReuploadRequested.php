<?php

namespace App\Mail;

use App\Models\StudentRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PassportReuploadRequested extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public readonly StudentRegistration $registration)
    {
    }

    public function build(): self
    {
        return $this
            ->subject(__('ap_registration.email.passport_subject', ['reference' => $this->registration->registration_number]))
            ->view('emails.passport-reupload-requested');
    }
}
