<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationPricingTier extends Model
{
    protected $fillable = [
        'exam_count',
        'reference_usd_per_exam',
        'combined_fee_per_exam',
        'exam_fee_per_exam',
        'service_fee_per_exam',
        'currency',
        'is_active',
    ];

    protected $casts = [
        'exam_count' => 'integer',
        'reference_usd_per_exam' => 'integer',
        'combined_fee_per_exam' => 'integer',
        'exam_fee_per_exam' => 'integer',
        'service_fee_per_exam' => 'integer',
        'is_active' => 'boolean',
    ];
}
