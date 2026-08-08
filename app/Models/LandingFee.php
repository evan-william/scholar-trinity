<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingFee extends Model
{
    protected $fillable = ['name', 'description', 'translations', 'currency', 'amount', 'sort_order', 'is_active'];

    protected $casts = [
        'amount' => 'integer',
        'is_active' => 'boolean',
        'translations' => 'array',
    ];
}
