<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class RegistrationExamSelection extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'student_registration_id',
        'ap_exam_subject_id',
        'selection_type',
        'exam_name',
        'exam_code',
        'category',
        'exam_date',
        'exam_fee',
        'service_fee',
        'late_fee',
        'practice_fee',
        'total_amount',
        'currency',
        'status',
        'metadata',
        'selected_at',
    ];

    protected $casts = [
        'exam_date' => 'date',
        'exam_fee' => 'integer',
        'service_fee' => 'integer',
        'late_fee' => 'integer',
        'practice_fee' => 'integer',
        'total_amount' => 'integer',
        'metadata' => 'array',
        'selected_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (RegistrationExamSelection $selection): void {
            $selection->uuid ??= (string) Str::uuid();
        });
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(StudentRegistration::class, 'student_registration_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(ApExamSubject::class, 'ap_exam_subject_id');
    }
}
