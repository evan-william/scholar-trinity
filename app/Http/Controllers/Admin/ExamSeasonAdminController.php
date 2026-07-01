<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpsertExamSeasonRequest;
use App\Models\ExamSeason;
use App\Services\ExamSeasonService;
use App\Services\SecurityAuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExamSeasonAdminController extends Controller
{
    public function index(): View
    {
        return view('admin.exam-seasons.index', [
            'seasons' => ExamSeason::query()
                ->withCount(['subjects', 'registrations'])
                ->orderByDesc('exam_year')
                ->orderByDesc('created_at')
                ->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.exam-seasons.form', [
            'season' => new ExamSeason([
                'timezone' => 'Asia/Taipei',
                'currency' => 'NTD',
                'status' => 'draft',
                'exam_year' => now()->addYear()->year,
                'default_service_fee' => 1200,
                'default_late_fee' => 1500,
            ]),
        ]);
    }

    public function store(UpsertExamSeasonRequest $request, ExamSeasonService $service): RedirectResponse
    {
        $season = ExamSeason::query()->create($request->validated() + [
            'is_active' => $request->boolean('is_active'),
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
        ]);

        if ($request->boolean('is_active')) {
            $service->setActive($season, $request->user()->id);
        }

        app(SecurityAuditService::class)->log('ap_registration', 'season', 'season_created', $season, [], $season->only(['season_name', 'academic_year', 'exam_year', 'status']));

        return redirect()->route('admin.exam-seasons.index')->with('status', 'Exam season created.');
    }

    public function edit(ExamSeason $examSeason): View
    {
        return view('admin.exam-seasons.form', ['season' => $examSeason]);
    }

    public function update(UpsertExamSeasonRequest $request, ExamSeason $examSeason, ExamSeasonService $service): RedirectResponse
    {
        if ($examSeason->status === 'archived' && ! $request->user()->is_admin) {
            abort(403);
        }

        $before = $examSeason->only(['season_name', 'exam_year', 'status', 'is_active', 'main_registration_start_at', 'main_registration_end_at', 'late_registration_start_at', 'late_registration_end_at']);
        $examSeason->update($request->validated() + [
            'is_active' => $request->boolean('is_active'),
            'updated_by' => $request->user()->id,
        ]);

        if ($request->boolean('is_active')) {
            $service->setActive($examSeason, $request->user()->id);
        }

        app(SecurityAuditService::class)->log('ap_registration', 'season', 'season_updated', $examSeason, $before, $examSeason->only(array_keys($before)));

        return redirect()->route('admin.exam-seasons.index')->with('status', 'Exam season updated.');
    }

    public function activate(Request $request, ExamSeason $examSeason, ExamSeasonService $service): RedirectResponse
    {
        $service->setActive($examSeason, $request->user()->id);

        return redirect()->route('admin.exam-seasons.index')->with('status', 'Active exam season changed.');
    }

    public function archive(Request $request, ExamSeason $examSeason, ExamSeasonService $service): RedirectResponse
    {
        $validated = $request->validate(['close_reason' => ['nullable', 'string', 'max:1000']]);
        $service->archive($examSeason, $request->user()->id, $validated['close_reason'] ?? null);

        return redirect()->route('admin.exam-seasons.index')->with('status', 'Exam season archived. Historical data remains readable.');
    }

    public function duplicate(Request $request, ExamSeason $examSeason, ExamSeasonService $service): RedirectResponse
    {
        $validated = $request->validate([
            'season_name' => ['required', 'string', 'max:160'],
            'academic_year' => ['required', 'string', 'max:40'],
            'exam_year' => ['required', 'integer', 'min:2020', 'max:2100'],
        ]);

        $newSeason = $service->duplicate($examSeason, $validated, $request->user()->id);

        return redirect()->route('admin.exam-seasons.edit', $newSeason)->with('status', 'Season duplicated. Registrations, passports, payments, receipts, and audit logs were not copied.');
    }

    public function destroy(ExamSeason $examSeason): RedirectResponse
    {
        abort(403, 'Historical seasons cannot be deleted. Archive them instead.');
    }
}
