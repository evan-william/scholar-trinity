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
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;

class StudentRegistrationAdminController extends Controller
{
    public function index(Request $request, StudentRegistrationRepository $repository): View
    {
        return view('admin.student-registrations.index', [
            'registrations' => $repository->search($request->only(['search', 'status', 'payment_status', 'document_status', 'verification_status', 'receipt_status', 'needs_accommodations', 'accommodation_status', 'preparation_interest', 'period', 'date_from', 'date_to', 'subject_id', 'season_id', 'sort', 'direction'])),
            'filters' => $request->only(['search', 'status', 'payment_status', 'document_status', 'verification_status', 'receipt_status', 'needs_accommodations', 'accommodation_status', 'preparation_interest', 'period', 'date_from', 'date_to', 'subject_id', 'season_id']),
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

    public function passportZip(Request $request): BinaryFileResponse
    {
        abort_unless(class_exists(ZipArchive::class), 422, 'ZipArchive extension is required for passport ZIP download.');

        $registrations = StudentRegistration::query()
            ->with('exams')
            ->whereNotNull('passport_file_path')
            ->when(trim((string) $request->query('search')), function ($query, string $search): void {
                $search = trim($search);
                $query->where(function ($inner) use ($search): void {
                    $inner->where('registration_number', 'like', "%{$search}%")
                        ->orWhere('student_full_name', 'like', "%{$search}%")
                        ->orWhere('student_email', 'like', "%{$search}%")
                        ->orWhere('passport_number', 'like', "%{$search}%");
                });
            })
            ->when($request->query('status'), fn ($query, string $status) => $query->where('status', $status))
            ->when($request->query('payment_status'), fn ($query, string $status) => $query->where('payment_status', $status))
            ->when($request->query('document_status'), fn ($query, string $status) => $query->where('passport_upload_status', $status))
            ->when($request->query('season_id'), fn ($query, $seasonId) => $query->where('exam_season_id', $seasonId))
            ->when($request->query('subject_id'), fn ($query, $subjectId) => $query->whereHas('exams', fn ($exam) => $exam->where('ap_exam_subjects.id', $subjectId)))
            ->latest('submitted_at')
            ->get();

        abort_if($registrations->isEmpty(), 404, 'No passport files found for the selected filters.');

        $disk = Storage::disk('local');
        $zipName = 'passport-files-'.now()->format('Ymd-His').'.zip';
        $zipPath = storage_path('app/'.$zipName);
        $zip = new ZipArchive();
        abort_unless($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true, 500, 'Unable to create passport ZIP.');

        $added = 0;
        foreach ($registrations as $registration) {
            if (! $registration->passport_file_path || ! $disk->exists($registration->passport_file_path)) {
                continue;
            }

            $name = $this->safeZipName($registration->registration_number.'-'.$registration->id.'-'.($registration->passport_original_name ?: 'passport'));
            $zip->addFile($disk->path($registration->passport_file_path), $name);
            $registration->update([
                'passport_last_downloaded_at' => now(),
                'passport_last_downloaded_by' => $request->user()->id,
            ]);
            $added++;
        }
        $zip->close();

        if ($added === 0) {
            @unlink($zipPath);
            abort(404, 'No readable passport files found for the selected filters.');
        }

        app(\App\Services\SecurityAuditService::class)->log('documents', 'passport_zip_downloaded', 'Passport ZIP downloaded.', null, [], [], [
            'file_count' => $added,
            'filters' => $request->query(),
        ], 'success', $request, $request->user()->id);

        return response()->download($zipPath, $zipName)->deleteFileAfterSend(true);
    }

    public function print(StudentRegistration $studentRegistration): View
    {
        return view('admin.student-registrations.print', [
            'registration' => $studentRegistration->load(['contact', 'exams', 'practiceExamSelections', 'histories']),
        ]);
    }

    private function safeZipName(string $name): string
    {
        $name = basename(str_replace(["\r", "\n", '"', '\\'], '', $name));
        $name = preg_replace('/[^A-Za-z0-9._ -]/', '_', $name) ?: 'passport';

        return trim($name) !== '' ? $name : 'passport';
    }
}
