<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class RegistrationAuditLog extends Model
{
    protected $fillable = [
        'uuid',
        'student_registration_id',
        'action',
        'field_name',
        'old_value',
        'new_value',
        'reason',
        'performed_by',
        'performed_ip',
        'performed_at',
    ];

    protected $casts = [
        'performed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (RegistrationAuditLog $log): void {
            $log->uuid ??= (string) Str::uuid();
            $log->performed_at ??= now();
        });
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(StudentRegistration::class, 'student_registration_id');
    }
}
