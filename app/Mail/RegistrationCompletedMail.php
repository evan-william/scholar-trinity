<?php

namespace App\Mail;

use App\Models\StudentRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationCompletedMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public readonly StudentRegistration $registration)
    {
    }

    public function build(): self
    {
        return $this
            ->subject('Registration completed: '.$this->registration->registration_number)
            ->view('emails.registration-completed')
            ->text('emails.registration-completed-text');
    }
}
