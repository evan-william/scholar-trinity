<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ReceiptRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'student_registration_id',
        'registration_payment_id',
        'buyer_name',
        'buyer_email',
        'buyer_phone',
        'company_name',
        'gui_tax_id',
        'receipt_type',
        'exam_fee_amount',
        'service_fee_amount',
        'late_fee_amount',
        'taxable_receipt_amount',
        'non_receipt_amount',
        'currency',
        'status',
        'receipt_number',
        'issued_at',
        'sent_at',
        'issued_by',
        'notes',
    ];

    protected $casts = [
        'exam_fee_amount' => 'integer',
        'service_fee_amount' => 'integer',
        'late_fee_amount' => 'integer',
        'taxable_receipt_amount' => 'integer',
        'non_receipt_amount' => 'integer',
        'issued_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (ReceiptRequest $receipt): void {
            $receipt->uuid ??= (string) Str::uuid();
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

    public function payment(): BelongsTo
    {
        return $this->belongsTo(RegistrationPayment::class, 'registration_payment_id');
    }

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(ReceiptLog::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(EInvoiceTransaction::class);
    }
}
