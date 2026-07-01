<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BackupLog extends Model
{
    protected $fillable = [
        'uuid',
        'backup_type',
        'status',
        'disk',
        'path',
        'size_bytes',
        'message',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'size_bytes' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (BackupLog $log): void {
            $log->uuid ??= (string) Str::uuid();
        });
    }
}
