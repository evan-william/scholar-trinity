<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamSeason;
use App\Models\PracticeExamOption;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PracticeExamOptionAdminController extends Controller
{
    public function index(): View
    {
        return view('admin.practice-exams.index', [
            'practiceExams' => PracticeExamOption::query()
                ->with('examSeason')
                ->withCount(['selections as registered_count' => fn ($query) => $query->where('selection_type', 'practice')->where('status', 'selected')])
                ->orderBy('sort_order')
                ->paginate(30),
            'seasons' => ExamSeason::query()->orderByDesc('exam_year')->get(),
            'categories' => config('registration.subject_categories', []),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'exam_season_id' => ['nullable', 'exists:exam_seasons,id'],
            'name' => ['required', 'string', 'max:160'],
            'category' => ['nullable', 'string', Rule::in(config('registration.subject_categories', []))],
            'practice_date' => ['nullable', 'date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'location' => ['nullable', 'string', 'max:160'],
            'fee' => ['required', 'integer', 'min:0', 'max:999999'],
            'seat_capacity' => ['nullable', 'integer', 'min:1', 'max:9999'],
            'currency' => ['required', 'string', 'max:8'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        PracticeExamOption::query()->create(array_merge($data, [
            'end_time' => null,
            'is_active' => $request->boolean('is_active'),
        ]));

        return redirect()->route('admin.practice-exams.index')->with('status', 'Practice exam option created.');
    }

    public function update(Request $request, PracticeExamOption $practiceExam): RedirectResponse
    {
        $data = $request->validate([
            'exam_season_id' => ['nullable', 'exists:exam_seasons,id'],
            'name' => ['required', 'string', 'max:160'],
            'category' => ['nullable', 'string', Rule::in(config('registration.subject_categories', []))],
            'practice_date' => ['nullable', 'date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'location' => ['nullable', 'string', 'max:160'],
            'fee' => ['required', 'integer', 'min:0', 'max:999999'],
            'seat_capacity' => ['nullable', 'integer', 'min:1', 'max:9999'],
            'currency' => ['required', 'string', 'max:8'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $practiceExam->update(array_merge($data, [
            'end_time' => null,
            'is_active' => $request->boolean('is_active'),
        ]));

        return redirect()->route('admin.practice-exams.index')->with('status', 'Practice exam option updated.');
    }

    public function destroy(PracticeExamOption $practiceExam): RedirectResponse
    {
        $practiceExam->update(['is_active' => false]);
        $practiceExam->delete();

        return redirect()->route('admin.practice-exams.index')->with('status', 'Practice exam option disabled.');
    }
}
