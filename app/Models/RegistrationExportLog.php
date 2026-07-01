<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class RegistrationExportLog extends Model
{
    protected $fillable = [
        'uuid',
        'export_type',
        'export_format',
        'file_name',
        'storage_disk',
        'storage_path',
        'filter_payload',
        'record_count',
        'exported_by',
        'exported_ip',
        'exported_at',
        'expires_at',
        'status',
    ];

    protected $casts = [
        'filter_payload' => 'array',
        'exported_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (RegistrationExportLog $log): void {
            $log->uuid ??= (string) Str::uuid();
            $log->exported_at ??= now();
        });
    }

    public function exporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'exported_by');
    }
}
