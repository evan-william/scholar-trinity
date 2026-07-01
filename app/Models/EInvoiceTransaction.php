<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class EInvoiceTransaction extends Model
{
    protected $fillable = [
        'uuid',
        'receipt_request_id',
        'provider',
        'provider_invoice_number',
        'provider_random_code',
        'provider_transaction_id',
        'provider_status',
        'request_payload',
        'response_payload',
        'error_message',
        'issued_at',
        'emailed_at',
    ];

    protected $casts = [
        'request_payload' => 'array',
        'response_payload' => 'array',
        'issued_at' => 'datetime',
        'emailed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (EInvoiceTransaction $transaction): void {
            $transaction->uuid ??= (string) Str::uuid();
        });
    }

    public function receipt(): BelongsTo
    {
        return $this->belongsTo(ReceiptRequest::class, 'receipt_request_id');
    }
}
