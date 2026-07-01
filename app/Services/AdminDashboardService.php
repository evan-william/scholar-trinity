<?php

namespace App\Services;

use App\Models\StudentRegistration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardService
{
    public function stats(array $filters = []): array
    {
        $query = StudentRegistration::query();
        $this->applyFilters($query, $filters);

        $today = (clone $query)->whereDate('created_at', today())->count();
        $week = (clone $query)->where('created_at', '>=', now()->startOfWeek())->count();
        $month = (clone $query)->where('created_at', '>=', now()->startOfMonth())->count();

        $paidQuery = StudentRegistration::query()->whereIn('status', ['paid', 'confirmed', 'completed']);
        $this->applyFilters($paidQuery, $filters);

        $lateQuery = StudentRegistration::query()->where('late_fee_total', '>', 0);
        $this->applyFilters($lateQuery, $filters);

        return [
            'totals' => [
                'registrations' => (clone $query)->count(),
                'today' => $today,
                'week' => $week,
                'month' => $month,
                'pending_payment' => $this->statusCount(['submitted', 'pending_payment', 'waiting_manual_verification'], $filters),
                'paid' => (clone $paidQuery)->count(),
                'incomplete' => $this->statusCount(['draft', 'missing_passport', 'missing_exam_selection', 'missing_payment_proof', 'invalid_document'], $filters),
                'late' => (clone $lateQuery)->count(),
                'total_revenue' => (clone $paidQuery)->sum('total_fee'),
                'exam_fee_revenue' => (clone $paidQuery)->sum('exam_fee_total'),
                'service_fee_revenue' => (clone $paidQuery)->sum('service_fee_total'),
                'late_fee_revenue' => (clone $paidQuery)->sum('late_fee_total'),
                'passport_pending_review' => 0,
            ],
            'byDay' => $this->registrationsByDay($filters),
            'byStatus' => $this->statusBreakdown($filters),
            'bySubject' => $this->subjectSummary($filters),
            'filters' => $filters,
        ];
    }

    private function statusCount(array $statuses, array $filters): int
    {
        $query = StudentRegistration::query()->whereIn('status', $statuses);
        $this->applyFilters($query, $filters);

        return $query->count();
    }

    private function registrationsByDay(array $filters): array
    {
        $query = StudentRegistration::query()
            ->selectRaw('date(created_at) as day, count(*) as total')
            ->groupBy('day')
            ->orderBy('day');
        $this->applyFilters($query, $filters);

        return $query->pluck('total', 'day')->all();
    }

    private function statusBreakdown(array $filters): array
    {
        $query = StudentRegistration::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->orderBy('status');
        $this->applyFilters($query, $filters);

        return $query->pluck('total', 'status')->all();
    }

    private function subjectSummary(array $filters): array
    {
        $query = DB::table('registration_exam_subjects as selections')
            ->join('ap_exam_subjects as subjects', 'subjects.id', '=', 'selections.ap_exam_subject_id')
            ->join('student_registrations as registrations', 'registrations.id', '=', 'selections.student_registration_id')
            ->whereNull('registrations.deleted_at')
            ->selectRaw('subjects.id, subjects.name, subjects.code, subjects.quota, subjects.status, count(selections.id) as selected_count, sum(selections.exam_fee) as exam_fee_total, sum(selections.service_fee) as service_fee_total, sum(selections.late_fee_snapshot) as late_fee_total')
            ->groupBy('subjects.id', 'subjects.name', 'subjects.code', 'subjects.quota', 'subjects.status')
            ->orderBy('subjects.name');

        if ($filters['status'] ?? null) {
            $query->where('registrations.status', $filters['status']);
        }
        if ($filters['subject_id'] ?? null) {
            $query->where('subjects.id', $filters['subject_id']);
        }
        if ($filters['date_from'] ?? null) {
            $query->whereDate('registrations.created_at', '>=', $filters['date_from']);
        }
        if ($filters['date_to'] ?? null) {
            $query->whereDate('registrations.created_at', '<=', $filters['date_to']);
        }

        return $query->get()->map(fn ($row) => [
            'name' => $row->name,
            'code' => $row->code,
            'selected_count' => (int) $row->selected_count,
            'quota' => $row->quota,
            'remaining' => $row->quota === null ? null : max(0, (int) $row->quota - (int) $row->selected_count),
            'status' => $row->status,
            'exam_fee_total' => (int) $row->exam_fee_total,
            'service_fee_total' => (int) $row->service_fee_total,
            'late_fee_total' => (int) $row->late_fee_total,
        ])->all();
    }

    private function applyFilters($query, array $filters): void
    {
        if ($filters['status'] ?? null) {
            $query->where('status', $filters['status']);
        }
        if ($filters['date_from'] ?? null) {
            $query->whereDate('created_at', '>=', Carbon::parse($filters['date_from']));
        }
        if ($filters['date_to'] ?? null) {
            $query->whereDate('created_at', '<=', Carbon::parse($filters['date_to']));
        }
        if ($filters['subject_id'] ?? null) {
            $query->whereHas('exams', fn ($examQuery) => $examQuery->where('ap_exam_subjects.id', $filters['subject_id']));
        }
        if (($filters['period'] ?? null) === 'late') {
            $query->where('late_fee_total', '>', 0);
        }
        if (($filters['period'] ?? null) === 'main') {
            $query->where('late_fee_total', '=', 0);
        }
    }
}
