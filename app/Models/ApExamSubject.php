<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ApExamSubject extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'exam_season_id',
        'name',
        'code',
        'category',
        'description',
        'exam_date',
        'start_time',
        'end_time',
        'timezone',
        'location',
        'quota',
        'registered_count',
        'exam_fee',
        'service_fee',
        'late_registration_fee',
        'currency',
        'status',
        'registration_open_at',
        'registration_close_at',
        'late_registration_start_at',
        'late_registration_end_at',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'exam_date' => 'date',
        'registration_open_at' => 'datetime',
        'registration_close_at' => 'datetime',
        'late_registration_start_at' => 'datetime',
        'late_registration_end_at' => 'datetime',
        'exam_fee' => 'integer',
        'service_fee' => 'integer',
        'late_registration_fee' => 'integer',
        'quota' => 'integer',
        'registered_count' => 'integer',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (ApExamSubject $subject): void {
            $subject->uuid ??= (string) Str::uuid();
        });
    }

    public function isSelectable(?\DateTimeInterface $at = null): bool
    {
        $now = $at ? \Illuminate\Support\Carbon::parse($at) : now();
        $status = strtolower($this->status);

        if ($this->examSeason && ! $this->examSeason->acceptsRegistration($now)) {
            return false;
        }

        if (! $this->is_active || ! in_array($status, ['open', 'limited'], true)) {
            return false;
        }

        if ($this->quota !== null && $this->registered_count >= $this->quota) {
            return false;
        }

        if ($this->registration_open_at && $now->lt($this->registration_open_at)) {
            return false;
        }

        if ($this->registration_close_at && $now->gt($this->registration_close_at)) {
            return false;
        }

        return true;
    }

    public function lateFeeApplies(?\DateTimeInterface $at = null): bool
    {
        $now = $at ? \Illuminate\Support\Carbon::parse($at) : now();

        if ($this->examSeason) {
            return $this->late_registration_fee > 0 && $this->examSeason->currentPeriod($now) === 'late';
        }

        return $this->late_registration_fee > 0
            && $this->late_registration_start_at
            && $this->late_registration_end_at
            && $now->betweenIncluded($this->late_registration_start_at, $this->late_registration_end_at);
    }

    public function examSeason(): BelongsTo
    {
        return $this->belongsTo(ExamSeason::class, 'exam_season_id');
    }

    public function registrations(): BelongsToMany
    {
        return $this->belongsToMany(StudentRegistration::class, 'registration_exam_subjects')
            ->withPivot(['status'])
            ->withTimestamps();
    }
}
