<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExportRegistrationsRequest;
use App\Http\Requests\StoreRegistrationAdminNoteRequest;
use App\Http\Requests\UpdateManagedRegistrationRequest;
use App\Http\Requests\UpdateStudentRegistrationStatusRequest;
use App\Http\Requests\VerifyRegistrationRequest;
use App\Models\ApExamSubject;
use App\Models\ExamSeason;
use App\Models\StudentRegistration;
use App\Repositories\StudentRegistrationRepository;
use App\Services\RegistrationExportService;
use App\Services\RegistrationManagementService;
use App\Services\StudentRegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentRegistrationAdminController extends Controller
{
    public function index(Request $request, StudentRegistrationRepository $repository): View
    {
        return view('admin.student-registrations.index', [
            'registrations' => $repository->search($request->only(['search', 'status', 'payment_status', 'document_status', 'verification_status', 'receipt_status', 'needs_accommodations', 'accommodation_status', 'period', 'date_from', 'date_to', 'subject_id', 'season_id', 'sort', 'direction'])),
            'filters' => $request->only(['search', 'status', 'payment_status', 'document_status', 'verification_status', 'receipt_status', 'needs_accommodations', 'accommodation_status', 'period', 'date_from', 'date_to', 'subject_id', 'season_id']),
            'subjects' => ApExamSubject::query()->orderBy('name')->get(),
            'seasons' => ExamSeason::query()->orderByDesc('exam_year')->get(),
        ]);
    }

    public function show(StudentRegistration $studentRegistration): View
    {
        Log::info('Registration viewed.', ['registration' => $studentRegistration->registration_number, 'admin_id' => request()->user()->id]);

        return view('admin.student-registrations.show', [
            'registration' => $studentRegistration->load(['contact', 'exams', 'practiceExamSelections', 'agreements', 'histories', 'adminNotes.author', 'auditLogs', 'verifier']),
        ]);
    }

    public function edit(StudentRegistration $studentRegistration): View
    {
        return view('admin.student-registrations.edit', [
            'registration' => $studentRegistration->load(['contact', 'exams', 'practiceExamSelections', 'histories']),
            'statuses' => ['submitted', 'pending_payment', 'paid', 'completed', 'cancelled', 'expired'],
            'paymentStatuses' => ['unpaid', 'pending_payment', 'waiting_verification', 'paid', 'failed', 'refunded', 'cancelled'],
            'subjects' => ApExamSubject::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function updateManaged(
        UpdateManagedRegistrationRequest $request,
        StudentRegistration $studentRegistration,
        RegistrationManagementService $service
    ): RedirectResponse {
        $service->update($studentRegistration, $request->validated(), $request->user()->id, $request->ip());

        return redirect()->route('admin.student-registrations.show', $studentRegistration)->with('status', 'Registration saved.');
    }

    public function update(
        UpdateStudentRegistrationStatusRequest $request,
        StudentRegistration $studentRegistration,
        StudentRegistrationService $service
    ): RedirectResponse {
        $service->updateStatus($studentRegistration, $request->validated('status'), $request->validated('note'));

        return redirect()->route('admin.student-registrations.show', $studentRegistration)->with('status', 'Registration updated.');
    }

    public function destroy(StudentRegistration $studentRegistration): RedirectResponse
    {
        $studentRegistration->delete();

        return redirect()->route('admin.student-registrations.index')->with('status', 'Registration deleted.');
    }

    public function verify(
        VerifyRegistrationRequest $request,
        StudentRegistration $studentRegistration,
        RegistrationManagementService $service
    ): RedirectResponse {
        $service->verify($studentRegistration, $request->validated(), $request->user()->id, $request->ip());

        return redirect()->route('admin.student-registrations.show', $studentRegistration)->with('status', 'Verification updated.');
    }

    public function addNote(
        StoreRegistrationAdminNoteRequest $request,
        StudentRegistration $studentRegistration,
        RegistrationManagementService $service
    ): RedirectResponse {
        $service->addNote($studentRegistration, $request->validated(), $request->user()->id, $request->ip());

        return redirect()->route('admin.student-registrations.show', $studentRegistration)->with('status', 'Internal note added.');
    }

    public function export(ExportRegistrationsRequest $request, RegistrationExportService $service): StreamedResponse
    {
        $export = $service->create($request->validated(), $request->user()->id, $request->ip());

        return Storage::disk($export->storage_disk)->download(
            $export->storage_path,
            $export->file_name,
            ['Content-Type' => RegistrationExportService::contentType($export->export_format)]
        );
    }

    public function print(StudentRegistration $studentRegistration): View
    {
        return view('admin.student-registrations.print', [
            'registration' => $studentRegistration->load(['contact', 'exams', 'practiceExamSelections', 'histories']),
        ]);
    }
}
