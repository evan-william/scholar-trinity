<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class AdminNotification extends Model
{
    protected $fillable = [
        'uuid',
        'type',
        'severity',
        'title',
        'body',
        'link_url',
        'student_registration_id',
        'registration_payment_id',
        'receipt_request_id',
        'payload',
        'read_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'read_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (AdminNotification $notification): void {
            $notification->uuid ??= (string) Str::uuid();
        });
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(StudentRegistration::class, 'student_registration_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(RegistrationPayment::class, 'registration_payment_id');
    }

    public function receipt(): BelongsTo
    {
        return $this->belongsTo(ReceiptRequest::class, 'receipt_request_id');
    }
}
