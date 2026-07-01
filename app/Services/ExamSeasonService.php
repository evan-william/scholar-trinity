<?php

namespace App\Services;

use App\Models\ApExamSubject;
use App\Models\ExamSeason;
use Illuminate\Support\Facades\DB;

class ExamSeasonService
{
    public function activeSeason(): ?ExamSeason
    {
        return ExamSeason::query()
            ->where('is_active', true)
            ->whereNull('archived_at')
            ->orderByDesc('exam_year')
            ->first();
    }

    public function setActive(ExamSeason $season, int $adminId): ExamSeason
    {
        return DB::transaction(function () use ($season, $adminId): ExamSeason {
            ExamSeason::query()->whereKeyNot($season->id)->update(['is_active' => false]);
            $season->update([
                'is_active' => true,
                'status' => in_array($season->status, ['draft', 'archived'], true) ? 'open' : $season->status,
                'updated_by' => $adminId,
            ]);
            app(SecurityAuditService::class)->log('ap_registration', 'season', 'active_season_changed', $season, [], $season->only(['season_name', 'exam_year', 'status']));

            return $season->fresh();
        });
    }

    public function archive(ExamSeason $season, int $adminId, ?string $reason = null): ExamSeason
    {
        $before = $season->only(['status', 'is_active', 'archived_at']);
        $season->update([
            'status' => 'archived',
            'is_active' => false,
            'close_reason' => $reason ?: $season->close_reason,
            'archived_at' => now(),
            'archived_by' => $adminId,
            'updated_by' => $adminId,
        ]);

        app(SecurityAuditService::class)->log('ap_registration', 'season', 'season_archived', $season, $before, $season->only(['status', 'is_active', 'archived_at']));

        return $season->fresh();
    }

    public function duplicate(ExamSeason $source, array $overrides, int $adminId): ExamSeason
    {
        return DB::transaction(function () use ($source, $overrides, $adminId): ExamSeason {
            $season = ExamSeason::query()->create([
                'season_name' => $overrides['season_name'] ?? $source->season_name.' Copy',
                'academic_year' => $overrides['academic_year'] ?? $source->academic_year,
                'exam_year' => $overrides['exam_year'] ?? ($source->exam_year + 1),
                'main_registration_start_at' => $overrides['main_registration_start_at'] ?? optional($source->main_registration_start_at)->addYear(),
                'main_registration_end_at' => $overrides['main_registration_end_at'] ?? optional($source->main_registration_end_at)->addYear(),
                'late_registration_start_at' => $overrides['late_registration_start_at'] ?? optional($source->late_registration_start_at)->addYear(),
                'late_registration_end_at' => $overrides['late_registration_end_at'] ?? optional($source->late_registration_end_at)->addYear(),
                'timezone' => $overrides['timezone'] ?? $source->timezone,
                'currency' => $overrides['currency'] ?? $source->currency,
                'default_service_fee' => $overrides['default_service_fee'] ?? $source->default_service_fee,
                'default_late_fee' => $overrides['default_late_fee'] ?? $source->default_late_fee,
                'status' => 'draft',
                'is_active' => false,
                'notes' => $overrides['notes'] ?? null,
                'cloned_from_id' => $source->id,
                'created_by' => $adminId,
                'updated_by' => $adminId,
            ]);

            $source->subjects()->withTrashed()->get()->each(function (ApExamSubject $subject) use ($season): void {
                $copy = $subject->replicate(['uuid', 'deleted_at', 'created_at', 'updated_at', 'registered_count']);
                $copy->exam_season_id = $season->id;
                $copy->code = $season->exam_year.'-'.$subject->code;
                $copy->registered_count = 0;
                $copy->status = $subject->status === 'cancelled' ? 'draft' : $subject->status;
                $copy->is_active = (bool) $subject->is_active;
                $copy->save();
            });

            app(SecurityAuditService::class)->log('ap_registration', 'season', 'season_duplicated', $season, [], [
                'source_season' => $source->season_name,
                'subjects_copied' => $season->subjects()->count(),
            ]);

            return $season->fresh(['subjects']);
        });
    }
}
