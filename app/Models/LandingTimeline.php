<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingTimeline extends Model
{
    protected $fillable = ['round', 'month', 'status', 'description', 'sort_order', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
