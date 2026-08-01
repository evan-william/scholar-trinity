<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRegistrationRequest;
use App\Models\PracticeExamOption;
use App\Models\StudentRegistration;
use App\Repositories\LandingContentRepository;
use App\Repositories\StudentRegistrationRepository;
use App\Services\FileSecurityService;
use App\Services\StudentRegistrationService;
use App\Services\PublicRegistrationSettings;
use App\Services\PaymentFlowService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class StudentRegistrationController extends Controller
{
    private const PASSPORT_DRAFT_SESSION_KEY = 'student_registration_passport_drafts';
    private const CONFIRMATION_ACCESS_SESSION_KEY = 'student_registration_confirmation_access';

    public function create(
        StudentRegistrationRepository $repository,
        LandingContentRepository $landingContent,
        PaymentFlowService $paymentFlow
    ): View
    {
        $catalogLoadFailed = false;

        try {
            $subjects = $repository->availableSubjects();
            $practiceExamOptions = PracticeExamOption::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();
        } catch (Throwable $exception) {
            report($exception);
            $subjects = collect();
            $practiceExamOptions = collect();
            $catalogLoadFailed = true;
        }

        return view('student-registration.create', [
            'subjects' => $subjects,
            'gradeLevels' => config('registration.grade_levels'),
            'practiceExamOptions' => $practiceExamOptions,
            'registrationSettings' => app(PublicRegistrationSettings::class)->all(),
            'registrationIntro' => data_get($landingContent->payload(), 'sections')->get('registration_intro'),
            'paymentSetting' => $paymentFlow->activeSetting(),
            'catalogLoadFailed' => $catalogLoadFailed,
        ]);
    }

    public function store(StoreStudentRegistrationRequest $request, StudentRegistrationService $service): RedirectResponse
    {
        $registration = $service->create(
            $request->validated(),
            $request->ip(),
            (string) $request->userAgent()
        );

        $request->session()->put(
            self::CONFIRMATION_ACCESS_SESSION_KEY,
            $registration->registration_number
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

    public function show(Request $request, string $registrationNumber): View|RedirectResponse
    {
        $allowedRegistrationNumber = (string) $request->session()->pull(
            self::CONFIRMATION_ACCESS_SESSION_KEY,
            ''
        );

        if ($allowedRegistrationNumber === '' || ! hash_equals($allowedRegistrationNumber, $registrationNumber)) {
            return redirect()
                ->route('landing')
                ->with('status', __('student_registration.confirmation_expired'));
        }

        $registration = StudentRegistration::query()
            ->with(['exams', 'practiceExamSelections'])
            ->where('registration_number', $registrationNumber)
            ->firstOrFail();

        return view('student-registration.show', compact('registration'));
    }
}
