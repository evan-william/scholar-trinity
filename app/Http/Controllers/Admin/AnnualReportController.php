<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamSeason;
use App\Services\AnnualReportService;
use App\Services\SecurityAuditService;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
}
