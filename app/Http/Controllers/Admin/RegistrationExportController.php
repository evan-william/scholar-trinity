<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExportRegistrationsRequest;
use App\Models\RegistrationExportLog;
use App\Services\RegistrationExportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RegistrationExportController extends Controller
{
    public function index(): View
    {
        return view('admin.exports.index', [
            'exports' => RegistrationExportLog::query()
                ->with('exporter')
                ->latest('exported_at')
                ->paginate(20),
        ]);
    }

    public function store(ExportRegistrationsRequest $request, RegistrationExportService $service): RedirectResponse
    {
        $export = $service->create($request->validated(), $request->user()->id, $request->ip());

        return redirect()->route('admin.exports.index')->with('status', 'Export created: '.$export->file_name);
    }

    public function download(RegistrationExportLog $registrationExportLog, Request $request): StreamedResponse
    {
        abort_if($registrationExportLog->expires_at?->isPast(), 410);
        abort_unless(Storage::disk($registrationExportLog->storage_disk)->exists($registrationExportLog->storage_path), 404);

        app(RegistrationExportService::class)->recordDownload($registrationExportLog, $request->user()->id, $request->ip());

        return Storage::disk($registrationExportLog->storage_disk)->download(
            $registrationExportLog->storage_path,
            $registrationExportLog->file_name,
            ['Content-Type' => RegistrationExportService::contentType($registrationExportLog->export_format)]
        );
    }
}
