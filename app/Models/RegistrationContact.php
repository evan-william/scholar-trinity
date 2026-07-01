<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationContact extends Model
{
    protected $fillable = [
        'student_registration_id',
        'parent_first_name',
        'parent_last_name',
        'parent_full_name',
        'relationship',
        'parent_email',
        'parent_phone',
        'mailing_address',
        'mailing_city',
        'postal_code',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(StudentRegistration::class, 'student_registration_id');
    }
}
