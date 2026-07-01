<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationStatusHistory extends Model
{
    protected $fillable = [
        'student_registration_id',
        'from_status',
        'to_status',
        'note',
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(StudentRegistration::class, 'student_registration_id');
    }
}
