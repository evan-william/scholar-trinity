<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRegistrationRequest;
use App\Models\ApExamSubject;
use App\Models\PracticeExamOption;
use App\Models\StudentRegistration;
use App\Repositories\LandingContentRepository;
use App\Repositories\StudentRegistrationRepository;
use App\Services\FileSecurityService;
use App\Services\StudentRegistrationService;
use App\Services\PublicRegistrationSettings;
use App\Services\PaymentFlowService;
use App\Services\RegistrationPricingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class StudentRegistrationController extends Controller
{
    private const PASSPORT_DRAFT_SESSION_KEY = 'student_registration_passport_drafts';
    private const CONFIRMATION_ACCESS_SESSION_KEY = 'student_registration_confirmation_access';

    public function create(
        StudentRegistrationRepository $repository,
        LandingContentRepository $landingContent,
        PaymentFlowService $paymentFlow,
        RegistrationPricingService $pricing
    ): View
    {
        $catalogLoadFailed = false;

        try {
            $subjects = $repository->availableSubjects();
            $practiceExamOptions = PracticeExamOption::query()
                ->where('is_active', true)
                ->withCount(['selections as registered_count' => fn ($query) => $query->where('selection_type', 'practice')->where('status', 'selected')])
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();
        } catch (Throwable $exception) {
            report($exception);
            $subjects = collect();
            $practiceExamOptions = collect();
            $catalogLoadFailed = true;
        }

        $landingPayload = $landingContent->payload();

        return view('student-registration.create', [
            'subjects' => $subjects,
            'gradeLevels' => config('registration.grade_levels'),
            'practiceExamOptions' => $practiceExamOptions,
            'registrationSettings' => app(PublicRegistrationSettings::class)->all(),
            'registrationIntro' => data_get($landingPayload, 'sections')->get('registration_intro'),
            'registrationNotice' => data_get($landingPayload, 'sections')->get('registration_notice'),
            'landingContact' => data_get($landingPayload, 'contact'),
            'pricingTiers' => $pricing->tiers(),
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

    public function validateStep(Request $request): JsonResponse
    {
        $step = (int) $request->input('step');

        if ($step < 1 || $step > 5) {
            return response()->json([
                'message' => 'Invalid registration step.',
                'errors' => ['step' => ['Invalid registration step.']],
            ], 422);
        }

        $validator = Validator::make(
            $request->all(),
            $this->stepRules($request, $step),
            (new StoreStudentRegistrationRequest())->messages()
        );

        if ($step === 3) {
            $validator->after(function ($validator) use ($request): void {
                $subjectUuids = array_values(array_unique((array) $request->input('exam_subject_uuids', [])));
                $subjects = ApExamSubject::query()
                    ->with('examSeason')
                    ->whereIn('uuid', $subjectUuids)
                    ->get();

                if ($subjects->count() !== count($subjectUuids)) {
                    $validator->errors()->add('exam_subject_uuids', __('student_registration.validation.exam_unavailable'));
                } elseif ($subjects->contains(fn (ApExamSubject $subject) => ! $subject->isSelectable())) {
                    $validator->errors()->add('exam_subject_uuids', __('student_registration.validation.exam_closed'));
                }

                $practiceUuids = array_values(array_unique((array) $request->input('practice_exams', [])));
                $activePracticeOptionsExist = PracticeExamOption::query()->where('is_active', true)->exists();

                if (! $activePracticeOptionsExist || $practiceUuids === []) {
                    return;
                }

                $options = PracticeExamOption::query()
                    ->where('is_active', true)
                    ->whereIn('uuid', $practiceUuids)
                    ->get();

                if ($options->count() !== count($practiceUuids)) {
                    $validator->errors()->add('practice_exams', __('student_registration.validation.practice_unavailable'));
                    return;
                }

                $fullOption = $options->first(function (PracticeExamOption $option): bool {
                    if ($option->seat_capacity === null) {
                        return false;
                    }

                    return $option->selections()
                        ->where('selection_type', 'practice')
                        ->where('status', 'selected')
                        ->count() >= $option->seat_capacity;
                });

                if ($fullOption) {
                    $validator->errors()->add('practice_exams', __('student_registration.validation.practice_full', [
                        'name' => $fullOption->name,
                    ]));
                }
            });
        }

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()->toArray(),
            ], 422);
        }

        return response()->json(['valid' => true, 'step' => $step]);
    }

    private function stepRules(Request $request, int $step): array
    {
        return match ($step) {
            1 => [
                'family_name_en' => ['required', 'string', 'max:80'],
                'first_name_en' => ['required', 'string', 'max:80'],
                'middle_initial' => ['nullable', 'string', 'max:3'],
                'middle_name' => ['nullable', 'string', 'max:80'],
                'chinese_legal_name' => ['required', 'string', 'max:120'],
                'date_of_birth' => ['required', 'date', 'before:today'],
                'nationality' => ['required', 'string', 'max:80'],
                'passport_number' => [
                    'required',
                    'string',
                    'max:30',
                    'regex:/^[A-Z0-9][A-Z0-9\-]{4,29}$/i',
                    Rule::unique('student_registrations', 'passport_number')->whereNull('deleted_at'),
                ],
                'passport_expiry_date' => ['nullable', 'date', 'after:today'],
                'grade' => ['required', 'string', 'max:40'],
                'current_school' => ['required', 'string', 'max:160'],
                'student_email' => [
                    'required',
                    'email',
                    'max:160',
                    Rule::unique('student_registrations', 'student_email')->whereNull('deleted_at'),
                ],
                'student_phone' => ['required', 'string', 'max:40', 'regex:/^\+?[0-9\s().-]{6,40}$/'],
                'passport_file_token' => [
                    'required',
                    'string',
                    'regex:/^[A-Za-z0-9]{40}$/',
                    function (string $attribute, mixed $value, \Closure $fail) use ($request): void {
                        if (! isset($request->session()->get(self::PASSPORT_DRAFT_SESSION_KEY, [])[$value])) {
                            $fail(__('student_registration.validation.passport_required'));
                        }
                    },
                ],
            ],
            2 => [
                'parent_first_name' => ['required', 'string', 'max:80'],
                'parent_last_name' => ['required', 'string', 'max:80'],
                'relationship' => ['required', 'string', 'max:60'],
                'parent_email' => ['required', 'email', 'max:160'],
                'parent_phone' => ['required', 'string', 'max:40', 'regex:/^\+?[0-9\s().-]{6,40}$/'],
                'mailing_address' => ['required', 'string', 'max:255'],
                'mailing_city' => ['required', 'string', 'max:100'],
                'postal_code' => ['nullable', 'string', 'max:12'],
                'emergency_contact_name' => ['required', 'string', 'max:140'],
                'emergency_contact_phone' => ['required', 'string', 'max:40', 'regex:/^\+?[0-9\s().-]{6,40}$/'],
                'emergency_contact_relationship' => ['required', 'string', 'max:60'],
            ],
            3 => [
                'exam_subject_uuids' => ['required', 'array', 'min:1'],
                'exam_subject_uuids.*' => ['uuid', 'distinct'],
                'practice_exams' => ['nullable', 'array'],
                'practice_exams.*' => ['string', 'max:120'],
            ],
            4 => [
                'needs_accommodations' => ['nullable', 'boolean'],
                'ssd_code' => ['nullable', 'required_if:needs_accommodations,1', 'string', 'max:60'],
                'accommodation_status' => ['nullable', 'required_if:needs_accommodations,1', 'in:approved,pending,new'],
                'accommodations' => ['nullable', 'array'],
                'accommodations.*.exam' => ['nullable', 'string', 'max:120'],
                'accommodations.*.request' => ['nullable', 'string', 'max:180'],
                'preparation_interest' => ['nullable', 'boolean'],
                'group_class_interest' => ['nullable', 'boolean'],
                'private_tutoring_interest' => ['nullable', 'boolean'],
                'preferred_tutoring_schedule' => ['nullable', 'string', 'max:160'],
                'preferred_tutoring_language' => ['nullable', 'string', 'max:40'],
                'preparation_notes' => ['nullable', 'string', 'max:1000'],
            ],
            5 => [
                'payment_method' => ['required', 'in:bank_transfer,manual_bank_transfer'],
                'student_signature_name' => ['required', 'string', 'max:140'],
                'student_signature_date' => ['required', 'date', 'before_or_equal:today'],
                'guardian_signature_name' => ['required', 'string', 'max:140'],
                'guardian_signature_date' => ['required', 'date', 'before_or_equal:today'],
                'accurate_information' => ['accepted'],
                'ap_policies' => ['accepted'],
                'privacy_policy' => ['accepted'],
                'terms_conditions' => ['accepted'],
                'confirmed_review' => ['accepted'],
            ],
        };
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
