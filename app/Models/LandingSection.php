<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingSection extends Model
{
    protected $fillable = ['key', 'title', 'eyebrow', 'body', 'items', 'is_active', 'sort_order'];

    protected $casts = [
        'items' => 'array',
        'is_active' => 'boolean',
    ];
}
