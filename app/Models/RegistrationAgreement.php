<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationAgreement extends Model
{
    protected $fillable = [
        'student_registration_id',
        'agreement_key',
        'accepted_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(StudentRegistration::class, 'student_registration_id');
    }
}
