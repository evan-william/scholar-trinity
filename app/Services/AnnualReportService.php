<?php

namespace App\Services;

use App\Models\ExamSeason;
use App\Models\ReceiptRequest;
use App\Models\RegistrationPayment;
use App\Models\StudentRegistration;
use Illuminate\Support\Collection;

class AnnualReportService
{
    public function build(?ExamSeason $season = null): array
    {
        $registrations = StudentRegistration::query()
            ->when($season, fn ($query) => $query->where('exam_season_id', $season->id));

        $payments = RegistrationPayment::query()
            ->whereHas('registration', fn ($query) => $query->when($season, fn ($inner) => $inner->where('exam_season_id', $season->id)));

        $receipts = ReceiptRequest::query()
            ->whereHas('registration', fn ($query) => $query->when($season, fn ($inner) => $inner->where('exam_season_id', $season->id)));

        $subjectRows = ($season?->subjects() ?? \App\Models\ApExamSubject::query())
            ->withCount(['registrations as paid_count' => fn ($query) => $query->where('student_registrations.payment_status', 'paid')])
            ->orderBy('sort_order')
            ->get()
            ->map(fn ($subject) => [
                'subject' => $subject,
                'remaining' => $subject->quota === null ? null : max(0, $subject->quota - $subject->registered_count),
            ]);

        return [
            'registration' => [
                'total' => (clone $registrations)->count(),
                'main' => (clone $registrations)->where(function ($query): void {
                    $query->where('registration_period_type', 'main')->orWhere(fn ($inner) => $inner->whereNull('registration_period_type')->where('late_fee_total', 0));
                })->count(),
                'late' => (clone $registrations)->where(function ($query): void {
                    $query->where('registration_period_type', 'late')->orWhere('late_fee_total', '>', 0);
                })->count(),
                'completed' => (clone $registrations)->whereIn('status', ['paid', 'completed'])->count(),
                'pending_payment' => (clone $registrations)->whereIn('payment_status', ['unpaid', 'pending_payment', 'waiting_verification'])->count(),
                'cancelled' => (clone $registrations)->where('status', 'cancelled')->count(),
                'verified' => (clone $registrations)->where('verification_status', 'verified')->count(),
                'needs_accommodations' => (clone $registrations)->where('needs_accommodations', true)->count(),
                'practice_exam_count' => (clone $registrations)->sum('practice_exam_count'),
            ],
            'revenue' => [
                'grand_total' => (clone $registrations)->sum('grand_total'),
                'exam_fee' => (clone $registrations)->sum('exam_fee_total'),
                'service_fee' => (clone $registrations)->sum('service_fee_total'),
                'late_fee' => (clone $registrations)->sum('late_fee_total'),
                'practice_exam' => (clone $registrations)->sum('practice_exam_total'),
                'paid' => (clone $payments)->where('payment_status', 'paid')->sum('grand_total'),
                'pending' => (clone $payments)->whereIn('payment_status', ['pending_payment', 'waiting_verification', 'unpaid'])->sum('grand_total'),
                'refunded' => (clone $payments)->where('payment_status', 'refunded')->sum('grand_total'),
                'receipt_eligible' => (clone $receipts)->sum('taxable_receipt_amount'),
            ],
            'payment_statuses' => (clone $payments)->selectRaw('payment_status, count(*) as total, sum(grand_total) as amount')->groupBy('payment_status')->get(),
            'receipt_statuses' => (clone $receipts)->selectRaw('status, count(*) as total, sum(taxable_receipt_amount) as amount')->groupBy('status')->get(),
            'subjects' => $subjectRows,
            'trend' => $this->trend((clone $registrations)->selectRaw('date(submitted_at) as date, count(*) as total')->whereNotNull('submitted_at')->groupBy('date')->orderBy('date')->get()),
        ];
    }

    /**
     * @return array<int, array<int, mixed>>
     */
    public function csvRows(?ExamSeason $season = null): array
    {
        $report = $this->build($season);
        $rows = [];

        foreach ($report['registration'] as $metric => $value) {
            $rows[] = ['registration', $metric, $value, null, $season?->season_name];
        }

        foreach ($report['revenue'] as $metric => $amount) {
            $rows[] = ['revenue', $metric, null, $amount, $season?->currency ?? 'NTD'];
        }

        foreach ($report['payment_statuses'] as $status) {
            $rows[] = ['payment_status', $status->payment_status, $status->total, $status->amount, null];
        }

        foreach ($report['receipt_statuses'] as $status) {
            $rows[] = ['receipt_status', $status->status, $status->total, $status->amount, null];
        }

        foreach ($report['subjects'] as $subjectRow) {
            $subject = $subjectRow['subject'];
            $rows[] = [
                'subject',
                $subject->code.' - '.$subject->name,
                $subject->registered_count,
                ($subject->exam_fee + $subject->service_fee + $subject->late_registration_fee) * $subject->registered_count,
                'remaining: '.($subjectRow['remaining'] ?? 'no cap').'; paid: '.$subject->paid_count,
            ];
        }

        foreach ($report['trend'] as $trendRow) {
            $rows[] = ['trend', $trendRow['date'], $trendRow['total'], null, null];
        }

        return $rows;
    }

    private function trend(Collection $rows): Collection
    {
        return $rows->map(fn ($row) => ['date' => $row->date, 'total' => (int) $row->total]);
    }
}
