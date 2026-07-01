<?php

namespace App\Http\Controllers;

use App\Models\ExamRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ExamRegistrationController extends Controller
{
    private const REGULAR_EXAM_FEE = 7800;
    private const PRACTICE_EXAM_FEE = 1800;
    private const SERVICE_FEE = 1200;
    private const LATE_REGISTRATION_FEE = 1500;

    public function create(): View
    {
        return view('exam-registration.create', [
            'regularExamFee' => self::REGULAR_EXAM_FEE,
            'practiceExamFee' => self::PRACTICE_EXAM_FEE,
            'serviceFee' => self::SERVICE_FEE,
            'lateRegistrationFee' => self::LATE_REGISTRATION_FEE,
            'regularExams' => $this->regularExams(),
            'practiceExams' => $this->practiceExams(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $allowedExams = collect($this->regularExams())
            ->merge($this->practiceExams())
            ->pluck('name')
            ->all();

        $validator = Validator::make($request->all(), [
            'registration_round' => ['required', 'in:regular,late'],
            'student_family_name' => ['required', 'string', 'max:80'],
            'student_first_name' => ['required', 'string', 'max:80'],
            'student_middle_initial' => ['nullable', 'string', 'max:5'],
            'student_middle_name' => ['nullable', 'string', 'max:80'],
            'student_chinese_name' => ['required', 'string', 'max:80'],
            'student_class_name' => ['nullable', 'string', 'max:120'],
            'grade' => ['required', 'string', 'max:20'],
            'school' => ['required', 'string', 'max:160'],
            'student_email' => ['required', 'email', 'max:160'],
            'student_phone' => ['required', 'string', 'max:40'],
            'passport' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'parent_first_name' => ['required', 'string', 'max:80'],
            'parent_last_name' => ['required', 'string', 'max:80'],
            'parent_email' => ['required', 'email', 'max:160'],
            'parent_phone' => ['required', 'string', 'max:40'],
            'relationship' => ['required', 'string', 'max:80'],
            'address_line_1' => ['required', 'string', 'max:200'],
            'address_line_2' => ['nullable', 'string', 'max:200'],
            'city' => ['required', 'string', 'max:120'],
            'postal_code' => ['nullable', 'string', 'max:30'],
            'country' => ['required', 'string', 'max:80'],
            'selected_exams' => ['required', 'array', 'min:1'],
            'selected_exams.*' => ['required', 'string', 'in:'.implode(',', $allowedExams)],
            'other_exams' => ['nullable', 'array'],
            'other_exams.*' => ['nullable', 'string', 'max:120'],
            'needs_accommodations' => ['nullable', 'boolean'],
            'ssd_code' => ['nullable', 'required_if:needs_accommodations,1', 'string', 'max:80'],
            'accommodation_status' => ['nullable', 'string', 'max:80'],
            'accommodation_exam' => ['nullable', 'array'],
            'accommodation_exam.*' => ['nullable', 'string', 'max:120'],
            'accommodation_detail' => ['nullable', 'array'],
            'accommodation_detail.*' => ['nullable', 'string', 'max:200'],
            'payment_method' => ['required', 'in:bank_transfer,cash,local_gateway,card_pending'],
            'receipt_type' => ['required', 'in:none,personal,business'],
            'receipt_title' => ['nullable', 'required_if:receipt_type,business', 'string', 'max:160'],
            'receipt_tax_id' => ['nullable', 'required_if:receipt_type,business', 'string', 'max:20'],
            'receipt_email' => ['nullable', 'email', 'max:160'],
            'terms' => ['accepted'],
        ], [
            'selected_exams.required' => 'Please select at least one AP or practice exam.',
            'passport.max' => 'Passport upload must be 10MB or smaller.',
        ]);

        $validator->validate();

        $selectedExamNames = $request->input('selected_exams', []);
        $regularCount = collect($selectedExamNames)->filter(fn ($name) => in_array($name, collect($this->regularExams())->pluck('name')->all(), true))->count();
        $practiceCount = count($selectedExamNames) - $regularCount;
        $examTotal = $regularCount * self::REGULAR_EXAM_FEE;
        $practiceTotal = $practiceCount * self::PRACTICE_EXAM_FEE;
        $lateTotal = $request->input('registration_round') === 'late' ? self::LATE_REGISTRATION_FEE : 0;
        $serviceTotal = self::SERVICE_FEE;
        $passport = $request->file('passport');
        $passportPath = $passport->store('passport-uploads', 'local');

        $registration = ExamRegistration::create([
            'reference_number' => $this->makeReferenceNumber(),
            'registration_round' => $request->input('registration_round'),
            'student_family_name' => $request->input('student_family_name'),
            'student_first_name' => $request->input('student_first_name'),
            'student_middle_initial' => $request->input('student_middle_initial'),
            'student_middle_name' => $request->input('student_middle_name'),
            'student_chinese_name' => $request->input('student_chinese_name'),
            'student_class_name' => $request->input('student_class_name'),
            'grade' => $request->input('grade'),
            'school' => $request->input('school'),
            'student_email' => $request->input('student_email'),
            'student_phone' => $request->input('student_phone'),
            'passport_path' => $passportPath,
            'passport_original_name' => $passport->getClientOriginalName(),
            'passport_mime_type' => $passport->getMimeType(),
            'passport_size' => $passport->getSize(),
            'parent_first_name' => $request->input('parent_first_name'),
            'parent_last_name' => $request->input('parent_last_name'),
            'parent_email' => $request->input('parent_email'),
            'parent_phone' => $request->input('parent_phone'),
            'relationship' => $request->input('relationship'),
            'address_line_1' => $request->input('address_line_1'),
            'address_line_2' => $request->input('address_line_2'),
            'city' => $request->input('city'),
            'postal_code' => $request->input('postal_code'),
            'country' => $request->input('country'),
            'selected_exams' => $this->examPayload($selectedExamNames),
            'other_exams' => collect($request->input('other_exams', []))->filter()->values()->all(),
            'regular_exam_count' => $regularCount,
            'practice_exam_count' => $practiceCount,
            'exam_fee_total' => $examTotal,
            'practice_fee_total' => $practiceTotal,
            'late_fee_total' => $lateTotal,
            'service_fee_total' => $serviceTotal,
            'total_due' => $examTotal + $practiceTotal + $lateTotal + $serviceTotal,
            'needs_accommodations' => $request->boolean('needs_accommodations'),
            'ssd_code' => $request->input('ssd_code'),
            'accommodation_status' => $request->input('accommodation_status'),
            'accommodations' => $this->accommodationPayload($request),
            'payment_method' => $request->input('payment_method'),
            'receipt_type' => $request->input('receipt_type'),
            'receipt_title' => $request->input('receipt_title'),
            'receipt_tax_id' => $request->input('receipt_tax_id'),
            'receipt_email' => $request->input('receipt_email') ?: $request->input('parent_email'),
            'terms_accepted_at' => now(),
            'payment_status' => 'pending',
        ]);

        return redirect()->route('registrations.show', $registration);
    }

    public function show(ExamRegistration $registration): View
    {
        return view('exam-registration.show', [
            'registration' => $registration,
        ]);
    }

    private function examPayload(array $selectedExamNames): array
    {
        $examLookup = collect($this->regularExams())
            ->mapWithKeys(fn ($exam) => [$exam['name'] => ['type' => 'regular', 'fee' => self::REGULAR_EXAM_FEE]])
            ->merge(collect($this->practiceExams())->mapWithKeys(fn ($exam) => [$exam['name'] => ['type' => 'practice', 'fee' => self::PRACTICE_EXAM_FEE]]));

        return collect($selectedExamNames)->map(fn ($name) => [
            'name' => $name,
            'type' => $examLookup[$name]['type'],
            'fee' => $examLookup[$name]['fee'],
        ])->values()->all();
    }

    private function accommodationPayload(Request $request): array
    {
        $exams = $request->input('accommodation_exam', []);
        $details = $request->input('accommodation_detail', []);

        return collect($exams)->map(fn ($exam, $index) => [
            'exam' => $exam,
            'detail' => $details[$index] ?? null,
        ])->filter(fn ($row) => $row['exam'] || $row['detail'])->values()->all();
    }

    private function makeReferenceNumber(): string
    {
        do {
            $number = 'AP2026-'.random_int(10000, 99999);
        } while (ExamRegistration::where('reference_number', $number)->exists());

        return $number;
    }

    private function regularExams(): array
    {
        return [
            ['name' => 'Biology', 'category' => 'Sciences'],
            ['name' => 'Chemistry', 'category' => 'Sciences'],
            ['name' => 'Physics 1', 'category' => 'Sciences'],
            ['name' => 'Physics C: Mechanics', 'category' => 'Sciences'],
            ['name' => 'Calculus AB', 'category' => 'Mathematics'],
            ['name' => 'Calculus BC', 'category' => 'Mathematics'],
            ['name' => 'Precalculus', 'category' => 'Mathematics'],
            ['name' => 'Statistics', 'category' => 'Mathematics'],
            ['name' => 'Computer Science A', 'category' => 'Computer Science'],
            ['name' => 'Computer Science Principles', 'category' => 'Computer Science'],
            ['name' => 'English Language and Composition', 'category' => 'English'],
            ['name' => 'English Literature and Composition', 'category' => 'English'],
            ['name' => 'Macroeconomics', 'category' => 'Social Sciences'],
            ['name' => 'Microeconomics', 'category' => 'Social Sciences'],
            ['name' => 'Psychology', 'category' => 'Social Sciences'],
            ['name' => 'United States History', 'category' => 'History'],
            ['name' => 'World History: Modern', 'category' => 'History'],
            ['name' => 'Chinese Language and Culture', 'category' => 'World Languages'],
        ];
    }

    private function practiceExams(): array
    {
        return [
            ['name' => 'Practice: Biology', 'category' => 'Practice Exams'],
            ['name' => 'Practice: English Language and Composition', 'category' => 'Practice Exams'],
            ['name' => 'Practice: Physics 1', 'category' => 'Practice Exams'],
            ['name' => 'Practice: Computer Science A', 'category' => 'Practice Exams'],
            ['name' => 'Practice: Calculus AB/BC', 'category' => 'Practice Exams'],
            ['name' => 'Practice: Macroeconomics', 'category' => 'Practice Exams'],
            ['name' => 'Practice: Precalculus', 'category' => 'Practice Exams'],
        ];
    }
}
