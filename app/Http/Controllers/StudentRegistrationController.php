<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRegistrationRequest;
use App\Models\StudentRegistration;
use App\Repositories\StudentRegistrationRepository;
use App\Services\StudentRegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudentRegistrationController extends Controller
{
    public function create(StudentRegistrationRepository $repository): View
    {
        return view('student-registration.create', [
            'subjects' => $repository->availableSubjects(),
            'gradeLevels' => config('registration.grade_levels'),
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

    public function show(string $registrationNumber): View
    {
        $registration = StudentRegistration::query()
            ->with(['contact', 'exams', 'agreements', 'histories'])
            ->where('registration_number', $registrationNumber)
            ->firstOrFail();

        return view('student-registration.show', compact('registration'));
    }
}
