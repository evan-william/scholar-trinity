<?php

namespace App\Repositories;

use App\Models\ApExamSubject;
use App\Models\ExamSeason;
use App\Models\StudentRegistration;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class StudentRegistrationRepository
{
    public function availableSubjects(): Collection
    {
        $activeSeason = ExamSeason::query()
            ->where('is_active', true)
            ->whereNull('archived_at')
            ->first();

        return ApExamSubject::query()
            ->with('examSeason')
            ->when($activeSeason, fn ($query) => $query->where('exam_season_id', $activeSeason->id))
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    public function selectableSubjects(): Collection
    {
        return $this->availableSubjects()
            ->filter(fn (ApExamSubject $subject) => $subject->isSelectable())
            ->values();
    }

    public function subjectsByIds(array $ids): Collection
    {
        return ApExamSubject::query()
            ->with('examSeason')
            ->whereIn('id', $ids)
            ->orderBy('sort_order')
            ->get();
    }

    public function subjectsByUuids(array $uuids): Collection
    {
        return ApExamSubject::query()
            ->with('examSeason')
            ->whereIn('uuid', $uuids)
            ->whereNull('deleted_at')
            ->orderBy('sort_order')
            ->get();
    }

    public function nextSequenceNumber(): int
    {
        return (int) StudentRegistration::withTrashed()->count() + 1;
    }

    public function search(array $filters): LengthAwarePaginator
    {
        return StudentRegistration::query()
            ->with(['contact', 'exams', 'examSeason'])
            ->withCount('exams')
            ->when(trim((string) ($filters['search'] ?? '')), function ($query, string $search): void {
                $search = trim($search);
                $query->where(function ($inner) use ($search): void {
                    $inner->where('registration_number', 'like', "%{$search}%")
                        ->orWhere('student_full_name', 'like', "%{$search}%")
                        ->orWhere('student_email', 'like', "%{$search}%")
                        ->orWhere('passport_number', 'like', "%{$search}%")
                        ->orWhereHas('contact', fn ($contact) => $contact->where('parent_email', 'like', "%{$search}%"));
                });
            })
            ->when($filters['status'] ?? null, fn ($query, string $status) => $query->where('status', $status))
            ->when($filters['payment_status'] ?? null, fn ($query, string $status) => $query->where('payment_status', $status))
            ->when($filters['season_id'] ?? null, fn ($query, $seasonId) => $query->where('exam_season_id', $seasonId))
            ->when($filters['subject_id'] ?? null, fn ($query, $subjectId) => $query->whereHas('exams', fn ($exam) => $exam->where('ap_exam_subjects.id', $subjectId)))
            ->when($filters['date_from'] ?? null, fn ($query, $date) => $query->whereDate('submitted_at', '>=', $date))
            ->when($filters['date_to'] ?? null, fn ($query, $date) => $query->whereDate('submitted_at', '<=', $date))
            ->when(($filters['period'] ?? null) === 'main', fn ($query) => $query->where(fn ($inner) => $inner->where('registration_period_type', 'main')->orWhere(fn ($fallback) => $fallback->whereNull('registration_period_type')->where('late_fee_total', 0))))
            ->when(($filters['period'] ?? null) === 'late', fn ($query) => $query->where(fn ($inner) => $inner->where('registration_period_type', 'late')->orWhere('late_fee_total', '>', 0)))
            ->orderBy($filters['sort'] ?? 'created_at', $filters['direction'] ?? 'desc')
            ->paginate(15)
            ->withQueryString();
    }
}
