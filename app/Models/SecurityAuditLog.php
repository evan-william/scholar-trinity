<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class SecurityAuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'uuid',
        'module',
        'event_type',
        'action',
        'auditable_type',
        'auditable_id',
        'user_id',
        'user_type',
        'ip_address',
        'user_agent',
        'old_values',
        'new_values',
        'metadata',
        'status',
        'created_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (SecurityAuditLog $log): void {
            $log->uuid ??= (string) Str::uuid();
            $log->created_at ??= now();
        });
    }

    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }
}
