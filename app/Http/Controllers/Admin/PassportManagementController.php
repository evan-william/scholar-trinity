<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReplacePassportRequest;
use App\Http\Requests\RequestPassportReuploadRequest;
use App\Http\Requests\UpdatePassportStatusRequest;
use App\Models\StudentRegistration;
use App\Services\PassportManagementService;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PassportManagementController extends Controller
{
    public function preview(StudentRegistration $studentRegistration, PassportManagementService $service): StreamedResponse
    {
        return $service->preview($studentRegistration, request()->user()->id);
    }

    public function download(StudentRegistration $studentRegistration, PassportManagementService $service): StreamedResponse
    {
        return $service->download($studentRegistration, request()->user()->id);
    }

    public function replace(
        ReplacePassportRequest $request,
        StudentRegistration $studentRegistration,
        PassportManagementService $service
    ): RedirectResponse {
        $service->replace(
            $studentRegistration,
            $request->file('passport'),
            $request->validated('reason'),
            $request->user()->id
        );

        return redirect()->route('admin.student-registrations.show', $studentRegistration)->with('status', 'Passport file replaced.');
    }

    public function status(
        UpdatePassportStatusRequest $request,
        StudentRegistration $studentRegistration,
        PassportManagementService $service
    ): RedirectResponse {
        $service->mark($studentRegistration, $request->validated(), $request->user()->id);

        return redirect()->route('admin.student-registrations.show', $studentRegistration)->with('status', 'Passport status updated.');
    }

    public function reupload(
        RequestPassportReuploadRequest $request,
        StudentRegistration $studentRegistration,
        PassportManagementService $service
    ): RedirectResponse {
        $service->requestReupload($studentRegistration, $request->validated(), $request->user()->id);

        return redirect()->route('admin.student-registrations.show', $studentRegistration)->with('status', 'Passport re-upload request sent.');
    }
}
