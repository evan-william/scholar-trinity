<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingRequiredDocument extends Model
{
    protected $fillable = ['name', 'description', 'translations', 'is_required', 'sort_order', 'is_active'];

    protected $casts = [
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'translations' => 'array',
    ];
}
