<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class PracticeExamOption extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'exam_season_id',
        'name',
        'category',
        'practice_date',
        'start_time',
        'end_time',
        'location',
        'fee',
        'currency',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'practice_date' => 'date',
        'fee' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (PracticeExamOption $option): void {
            $option->uuid ??= (string) Str::uuid();
        });
    }

    public function examSeason(): BelongsTo
    {
        return $this->belongsTo(ExamSeason::class, 'exam_season_id');
    }
}
