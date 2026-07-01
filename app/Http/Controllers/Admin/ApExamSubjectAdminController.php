<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpsertApExamSubjectRequest;
use App\Models\ApExamSubject;
use App\Models\ExamSeason;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ApExamSubjectAdminController extends Controller
{
    public function index(): View
    {
        return view('admin.ap-exam-subjects.index', [
            'subjects' => ApExamSubject::query()->with('examSeason')->orderBy('sort_order')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.ap-exam-subjects.form', [
            'subject' => new ApExamSubject(['timezone' => 'Asia/Taipei', 'currency' => 'NTD', 'status' => 'draft', 'is_active' => true]),
            'seasons' => ExamSeason::query()->orderByDesc('exam_year')->get(),
        ]);
    }

    public function store(UpsertApExamSubjectRequest $request): RedirectResponse
    {
        $subject = ApExamSubject::query()->create($request->validated() + ['is_active' => $request->boolean('is_active')]);
        Log::info('Admin created AP exam subject.', ['code' => $subject->code]);

        return redirect()->route('admin.ap-exam-subjects.index')->with('status', 'Exam subject created.');
    }

    public function edit(ApExamSubject $apExamSubject): View
    {
        return view('admin.ap-exam-subjects.form', [
            'subject' => $apExamSubject,
            'seasons' => ExamSeason::query()->orderByDesc('exam_year')->get(),
        ]);
    }

    public function update(UpsertApExamSubjectRequest $request, ApExamSubject $apExamSubject): RedirectResponse
    {
        $before = $apExamSubject->only(['exam_fee', 'service_fee', 'late_registration_fee', 'status']);
        $apExamSubject->update($request->validated() + ['is_active' => $request->boolean('is_active')]);
        Log::info('Admin updated AP exam subject.', ['code' => $apExamSubject->code, 'before' => $before, 'after' => $apExamSubject->only(array_keys($before))]);

        return redirect()->route('admin.ap-exam-subjects.index')->with('status', 'Exam subject updated.');
    }

    public function destroy(ApExamSubject $apExamSubject): RedirectResponse
    {
        $apExamSubject->update(['is_active' => false, 'status' => 'disabled']);
        $apExamSubject->delete();

        return redirect()->route('admin.ap-exam-subjects.index')->with('status', 'Exam subject disabled.');
    }
}
