<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ExamSeason extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'season_name',
        'academic_year',
        'exam_year',
        'main_registration_start_at',
        'main_registration_end_at',
        'late_registration_start_at',
        'late_registration_end_at',
        'timezone',
        'currency',
        'default_service_fee',
        'default_late_fee',
        'status',
        'is_active',
        'public_status_message',
        'close_reason',
        'reopen_reason',
        'notes',
        'cloned_from_id',
        'created_by',
        'updated_by',
        'archived_at',
        'archived_by',
    ];

    protected $casts = [
        'main_registration_start_at' => 'datetime',
        'main_registration_end_at' => 'datetime',
        'late_registration_start_at' => 'datetime',
        'late_registration_end_at' => 'datetime',
        'default_service_fee' => 'integer',
        'default_late_fee' => 'integer',
        'exam_year' => 'integer',
        'is_active' => 'boolean',
        'archived_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (ExamSeason $season): void {
            $season->uuid ??= (string) Str::uuid();
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(ApExamSubject::class, 'exam_season_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(StudentRegistration::class, 'exam_season_id');
    }

    public function clonedFrom(): BelongsTo
    {
        return $this->belongsTo(self::class, 'cloned_from_id');
    }

    public function currentPeriod(?\DateTimeInterface $at = null): string
    {
        if ($this->status === 'archived') {
            return 'archived';
        }

        if (in_array($this->status, ['closed', 'draft'], true)) {
            return $this->status;
        }

        $now = $at ? Carbon::parse($at, $this->timezone) : now($this->timezone);

        if ($this->main_registration_start_at && $this->main_registration_end_at
            && $now->betweenIncluded($this->main_registration_start_at->timezone($this->timezone), $this->main_registration_end_at->timezone($this->timezone))) {
            return 'main';
        }

        if ($this->late_registration_start_at && $this->late_registration_end_at
            && $now->betweenIncluded($this->late_registration_start_at->timezone($this->timezone), $this->late_registration_end_at->timezone($this->timezone))) {
            return 'late';
        }

        if ($this->status === 'open' || $this->status === 'late_registration') {
            return 'not_open';
        }

        return $this->status ?: 'not_open';
    }

    public function publicStatus(?\DateTimeInterface $at = null): string
    {
        return match ($this->currentPeriod($at)) {
            'main' => 'open',
            'late' => 'late_registration',
            'archived' => 'archived',
            'closed' => 'closed',
            'draft' => 'draft',
            default => 'not_open',
        };
    }

    public function acceptsRegistration(?\DateTimeInterface $at = null): bool
    {
        return $this->is_active
            && ! $this->archived_at
            && in_array($this->currentPeriod($at), ['main', 'late'], true);
    }
}
