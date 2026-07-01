<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingContact extends Model
{
    protected $fillable = ['organization', 'email', 'phone', 'whatsapp', 'office_hours', 'address', 'map_url', 'social_links'];

    protected $casts = [
        'social_links' => 'array',
    ];
}
