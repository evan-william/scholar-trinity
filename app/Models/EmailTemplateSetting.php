<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EmailTemplateSetting extends Model
{
    protected $fillable = [
        'uuid',
        'template_key',
        'locale',
        'subject',
        'body_html',
        'body_text',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (EmailTemplateSetting $template): void {
            $template->uuid ??= (string) Str::uuid();
        });
    }
}
