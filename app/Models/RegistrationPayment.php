<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class RegistrationPayment extends Model
{
    protected $fillable = [
        'uuid',
        'student_registration_id',
        'payment_reference',
        'provider',
        'payment_method',
        'payment_status',
        'exam_fee_amount',
        'service_fee_amount',
        'late_fee_amount',
        'grand_total',
        'currency',
        'transaction_id',
        'gateway_order_id',
        'gateway_payload',
        'proof_file_path',
        'proof_original_name',
        'proof_mime_type',
        'proof_file_size',
        'proof_uploaded_at',
        'payment_deadline_at',
        'paid_at',
        'verified_by',
        'verified_at',
        'rejected_reason',
    ];

    protected $casts = [
        'gateway_payload' => 'array',
        'proof_uploaded_at' => 'datetime',
        'payment_deadline_at' => 'datetime',
        'paid_at' => 'datetime',
        'verified_at' => 'datetime',
        'exam_fee_amount' => 'integer',
        'service_fee_amount' => 'integer',
        'late_fee_amount' => 'integer',
        'grand_total' => 'integer',
        'proof_file_size' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (RegistrationPayment $payment): void {
            $payment->uuid ??= (string) Str::uuid();
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(StudentRegistration::class, 'student_registration_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(PaymentLog::class);
    }

    public function receiptRequest(): HasOne
    {
        return $this->hasOne(ReceiptRequest::class);
    }
}
