<?php

namespace App\Services;

use App\Models\AdminNotification;
use App\Models\ReceiptRequest;
use App\Models\RegistrationPayment;
use App\Models\StudentRegistration;

class AdminNotificationService
{
    /**
     * @param array<string, mixed> $payload
     */
    public function create(
        string $type,
        string $title,
        ?string $body = null,
        string $severity = 'info',
        ?string $linkUrl = null,
        ?StudentRegistration $registration = null,
        ?RegistrationPayment $payment = null,
        ?ReceiptRequest $receipt = null,
        array $payload = [],
    ): AdminNotification {
        return AdminNotification::query()->create([
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'severity' => $severity,
            'link_url' => $linkUrl,
            'student_registration_id' => $registration?->id ?? $payment?->student_registration_id ?? $receipt?->student_registration_id,
            'registration_payment_id' => $payment?->id ?? $receipt?->registration_payment_id,
            'receipt_request_id' => $receipt?->id,
            'payload' => $payload,
        ]);
    }
}
