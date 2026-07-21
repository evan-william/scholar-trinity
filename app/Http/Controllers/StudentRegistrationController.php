<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRegistrationRequest;
use App\Models\PracticeExamOption;
use App\Models\StudentRegistration;
use App\Repositories\StudentRegistrationRepository;
use App\Services\FileSecurityService;
use App\Services\StudentRegistrationService;
use App\Services\PublicRegistrationSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class StudentRegistrationController extends Controller
{
    private const PASSPORT_DRAFT_SESSION_KEY = 'student_registration_passport_drafts';

    public function create(StudentRegistrationRepository $repository): View
    {
        try {
            $subjects = $repository->availableSubjects();
            $practiceExamOptions = PracticeExamOption::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();
        } catch (Throwable) {
            $subjects = $this->fallbackSubjects();
            $practiceExamOptions = collect();
        }

        return view('student-registration.create', [
            'subjects' => $subjects,
            'gradeLevels' => config('registration.grade_levels'),
            'practiceExamOptions' => $practiceExamOptions,
            'registrationSettings' => app(PublicRegistrationSettings::class)->all(),
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

    private function fallbackSubjects(): Collection
    {
        return collect([
            ['uuid' => 'fallback-bio', 'name' => 'Biology', 'code' => 'BIO', 'category' => 'Sciences'],
            ['uuid' => 'fallback-chem', 'name' => 'Chemistry', 'code' => 'CHEM', 'category' => 'Sciences'],
            ['uuid' => 'fallback-phy1', 'name' => 'Physics 1', 'code' => 'PHY1', 'category' => 'Sciences'],
            ['uuid' => 'fallback-calab', 'name' => 'Calculus AB', 'code' => 'CALAB', 'category' => 'Mathematics'],
            ['uuid' => 'fallback-calbc', 'name' => 'Calculus BC', 'code' => 'CALBC', 'category' => 'Mathematics'],
            ['uuid' => 'fallback-stat', 'name' => 'Statistics', 'code' => 'STAT', 'category' => 'Mathematics'],
            ['uuid' => 'fallback-csa', 'name' => 'Computer Science A', 'code' => 'CSA', 'category' => 'General'],
            ['uuid' => 'fallback-englang', 'name' => 'English Language and Composition', 'code' => 'ENGLANG', 'category' => 'General'],
            ['uuid' => 'fallback-macro', 'name' => 'Macroeconomics', 'code' => 'MACRO', 'category' => 'General'],
            ['uuid' => 'fallback-chn', 'name' => 'Chinese Language and Culture', 'code' => 'CHN', 'category' => 'General'],
        ])->map(fn (array $subject, int $index) => new class($subject, $index) {
            public string $uuid;
            public string $name;
            public string $code;
            public string $category;
            public string $status = 'open';
            public bool $is_active = true;
            public ?object $exam_date = null;
            public int $exam_fee = 7800;
            public int $service_fee = 1200;
            public int $late_registration_fee = 1500;
            public int $sort_order;

            public function __construct(array $subject, int $index)
            {
                $this->uuid = $subject['uuid'];
                $this->name = $subject['name'];
                $this->code = $subject['code'];
                $this->category = $subject['category'];
                $this->sort_order = $index;
            }

            public function isSelectable(): bool
            {
                return true;
            }

            public function lateFeeApplies(): bool
            {
                return true;
            }
        });
    }
}
