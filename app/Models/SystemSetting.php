<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SystemSetting extends Model
{
    protected $fillable = [
        'uuid',
        'group',
        'key',
        'value',
        'type',
        'description',
        'is_public',
        'updated_by',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (SystemSetting $setting): void {
            $setting->uuid ??= (string) Str::uuid();
        });
    }
}
