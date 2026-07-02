<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamSeason;
use App\Services\AnnualReportService;
use App\Services\SecurityAuditService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AnnualReportController extends Controller
{
    public function index(Request $request, AnnualReportService $service): View
    {
        $season = $request->filled('season')
            ? ExamSeason::query()->where('uuid', $request->string('season'))->first()
            : ExamSeason::query()->where('is_active', true)->first();

        app(SecurityAuditService::class)->log('ap_registration', 'report', 'annual_report_viewed', $season);

        return view('admin.reports.annual', [
            'seasons' => ExamSeason::query()->orderByDesc('exam_year')->get(),
            'selectedSeason' => $season,
            'report' => $service->build($season),
        ]);
    }

    public function export(Request $request, AnnualReportService $service): StreamedResponse
    {
        $season = $request->filled('season')
            ? ExamSeason::query()->where('uuid', $request->string('season'))->first()
            : ExamSeason::query()->where('is_active', true)->first();

        app(SecurityAuditService::class)->log('ap_registration', 'report_export', 'annual_report_exported', $season);

        $rows = $service->csvRows($season);
        $fileName = 'annual-report-'.($season?->exam_year ?? now()->year).'-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($rows): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Section', 'Metric', 'Value', 'Amount', 'Notes']);
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, $fileName, ['Content-Type' => 'text/csv; charset=utf-8']);
    }
}
