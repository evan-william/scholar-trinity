<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRegistrationRequest;
use App\Models\PracticeExamOption;
use App\Models\StudentRegistration;
use App\Repositories\StudentRegistrationRepository;
use App\Services\FileSecurityService;
use App\Services\StudentRegistrationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StudentRegistrationController extends Controller
{
    private const PASSPORT_DRAFT_SESSION_KEY = 'student_registration_passport_drafts';

    public function create(StudentRegistrationRepository $repository): View
    {
        return view('student-registration.create', [
            'subjects' => $repository->availableSubjects(),
            'gradeLevels' => config('registration.grade_levels'),
            'practiceExamOptions' => PracticeExamOption::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(StoreStudentRegistrationRequest $request, StudentRegistrationService $service): RedirectResponse
    {
        $registration = $service->create(
            $request->validated(),
            $request->ip(),
            (string) $request->userAgent()
        );

        return redirect()->route('student-registrations.show', $registration->registration_number);
    }

    public function storePassportDraft(Request $request, FileSecurityService $fileSecurity): JsonResponse
    {
        $validated = $request->validate([
            'passport_file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);

        $file = $validated['passport_file'];
        $fileSecurity->validate($file, 'passport_file');

        $token = Str::random(40);
        $extension = $file->getClientOriginalExtension() ?: 'upload';
        $path = $file->storeAs('registration-drafts/passports', $token.'.'.$extension, 'local');
        $drafts = $request->session()->get(self::PASSPORT_DRAFT_SESSION_KEY, []);

        $drafts[$token] = [
            'path' => $path,
            'name' => basename($file->getClientOriginalName()),
            'mime' => $file->getMimeType(),
            'size' => $file->getSize(),
        ];

        $request->session()->put(self::PASSPORT_DRAFT_SESSION_KEY, $drafts);

        return response()->json([
            'token' => $token,
            'name' => $drafts[$token]['name'],
        ]);
    }

    public function show(string $registrationNumber): View
    {
        $registration = StudentRegistration::query()
            ->with(['contact', 'exams', 'practiceExamSelections', 'agreements', 'histories'])
            ->where('registration_number', $registrationNumber)
            ->firstOrFail();

        return view('student-registration.show', compact('registration'));
    }
}
