<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ReceiptLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'uuid',
        'receipt_request_id',
        'student_registration_id',
        'event_type',
        'old_status',
        'new_status',
        'payload',
        'performed_by',
        'performed_ip',
        'created_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'created_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (ReceiptLog $log): void {
            $log->uuid ??= (string) Str::uuid();
            $log->created_at ??= now();
        });
    }

    public function receipt(): BelongsTo
    {
        return $this->belongsTo(ReceiptRequest::class, 'receipt_request_id');
    }
}
