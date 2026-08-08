<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingFaq extends Model
{
    protected $fillable = ['question', 'answer', 'translations', 'sort_order', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'translations' => 'array',
    ];
}
