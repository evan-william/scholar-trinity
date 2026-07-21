<?php

namespace App\Mail;

use App\Models\StudentRegistration;
use App\Services\EmailTemplateRenderer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StudentRegistrationConfirmation extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public readonly StudentRegistration $registration)
    {
    }

    public function build(): self
    {
        $locale = app()->getLocale() === 'zh-TW' ? 'zh_TW' : app()->getLocale();
        $locale = in_array($locale, ['en', 'zh_TW'], true) ? $locale : 'en';
        $rendered = app(EmailTemplateRenderer::class)->render('student_registration_confirmation', $locale, [
            'student_name' => $this->registration->student_full_name,
            'registration_number' => $this->registration->registration_number,
            'submitted_at' => optional($this->registration->submitted_at)->format('Y-m-d H:i'),
            'selected_exams' => $this->registration->exams->pluck('name')->join(', '),
        ]);

        if ($rendered) {
            return $this
                ->subject($rendered['subject'])
                ->view('emails.configured-template', ['bodyHtml' => $rendered['html']])
                ->text('emails.configured-template-text', ['bodyText' => $rendered['text']]);
        }

        return $this
            ->subject(__('ap_registration.email.registration_subject', ['reference' => $this->registration->registration_number]))
            ->view('emails.student-registration-confirmation')
            ->text('emails.student-registration-confirmation-text');
    }
}
