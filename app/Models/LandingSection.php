<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingSection extends Model
{
    protected $fillable = ['key', 'title', 'eyebrow', 'body', 'items', 'translations', 'is_active', 'sort_order'];

    protected $casts = [
        'items' => 'array',
        'translations' => 'array',
        'is_active' => 'boolean',
    ];
}
