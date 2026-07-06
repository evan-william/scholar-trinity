<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamSeason;
use App\Models\PracticeExamOption;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PracticeExamOptionAdminController extends Controller
{
    public function index(): View
    {
        return view('admin.practice-exams.index', [
            'practiceExams' => PracticeExamOption::query()->with('examSeason')->orderBy('sort_order')->paginate(30),
            'seasons' => ExamSeason::query()->orderByDesc('exam_year')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'exam_season_id' => ['nullable', 'exists:exam_seasons,id'],
            'name' => ['required', 'string', 'max:160'],
            'category' => ['nullable', 'string', 'max:100'],
            'practice_date' => ['nullable', 'date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after:start_time'],
            'location' => ['nullable', 'string', 'max:160'],
            'fee' => ['required', 'integer', 'min:0', 'max:999999'],
            'currency' => ['required', 'string', 'max:8'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        PracticeExamOption::query()->create($data + ['is_active' => $request->boolean('is_active')]);

        return redirect()->route('admin.practice-exams.index')->with('status', 'Practice exam option created.');
    }

    public function update(Request $request, PracticeExamOption $practiceExam): RedirectResponse
    {
        $data = $request->validate([
            'exam_season_id' => ['nullable', 'exists:exam_seasons,id'],
            'name' => ['required', 'string', 'max:160'],
            'category' => ['nullable', 'string', 'max:100'],
            'practice_date' => ['nullable', 'date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after:start_time'],
            'location' => ['nullable', 'string', 'max:160'],
            'fee' => ['required', 'integer', 'min:0', 'max:999999'],
            'currency' => ['required', 'string', 'max:8'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $practiceExam->update($data + ['is_active' => $request->boolean('is_active')]);

        return redirect()->route('admin.practice-exams.index')->with('status', 'Practice exam option updated.');
    }

    public function destroy(PracticeExamOption $practiceExam): RedirectResponse
    {
        $practiceExam->update(['is_active' => false]);
        $practiceExam->delete();

        return redirect()->route('admin.practice-exams.index')->with('status', 'Practice exam option disabled.');
    }
}
