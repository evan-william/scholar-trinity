<?php

namespace App\Services;

use App\Models\RegistrationExportLog;
use App\Models\StudentRegistration;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class RegistrationExportService
{
    /**
     * @param array<string, mixed> $filters
     */
    public function create(array $filters, int $adminId, ?string $ipAddress): RegistrationExportLog
    {
        $format = $filters['format'] ?? 'csv';
        $format = $format === 'xls' ? 'xlsx' : $format;
        $template = $filters['template'] ?? 'standard';
        $fileName = 'student-registrations-'.now()->format('Ymd-His').'.'.$format;
        $path = 'registration-exports/'.$fileName;

        Log::info('Registration export requested.', ['admin_id' => $adminId, 'filters' => $filters]);
        app(SecurityAuditService::class)->log('export', 'export_requested', 'Registration export requested.', null, [], [], ['filters' => $filters], 'success', request(), $adminId);

        $registrations = $this->query($filters)->get();
        $rows = $this->rows($registrations, $filters, $template);
        $content = $format === 'xlsx' ? $this->xlsx($rows) : $this->csv($rows);

        Storage::disk('local')->put($path, $content);

        $export = RegistrationExportLog::query()->create([
            'export_type' => $template,
            'export_format' => $format,
            'file_name' => $fileName,
            'storage_disk' => 'local',
            'storage_path' => $path,
            'filter_payload' => $filters,
            'record_count' => $registrations->count(),
            'exported_by' => $adminId,
            'exported_ip' => $ipAddress,
            'expires_at' => now()->addDays(7),
            'status' => 'completed',
        ]);

        Log::info('Registration export completed.', ['export_uuid' => $export->uuid, 'records' => $export->record_count]);
        app(SecurityAuditService::class)->log('export', 'export_completed', 'Registration export completed.', $export, [], [], ['records' => $export->record_count], 'success', request(), $adminId);

        return $export;
    }

    public function recordDownload(RegistrationExportLog $export, int $adminId, ?string $ipAddress): void
    {
        Log::info('Registration export downloaded.', [
            'export_uuid' => $export->uuid,
            'admin_id' => $adminId,
            'ip' => $ipAddress,
        ]);
        app(SecurityAuditService::class)->log('export', 'export_downloaded', 'Registration export downloaded.', $export, [], [], [], 'success', request(), $adminId);
    }

    public static function contentType(string $format): string
    {
        return $format === 'xlsx'
            ? 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            : 'text/csv; charset=utf-8';
    }

    /**
     * @param array<string, mixed> $filters
     */
    private function query(array $filters): Builder
    {
        return StudentRegistration::query()
            ->with(['contact', 'exams', 'adminNotes'])
            ->when(trim((string) ($filters['search'] ?? '')), function (Builder $query, string $search): void {
                $search = trim($search);
                $query->where(function (Builder $inner) use ($search): void {
                    $inner->where('registration_number', 'like', "%{$search}%")
                        ->orWhere('student_full_name', 'like', "%{$search}%")
                        ->orWhere('student_email', 'like', "%{$search}%")
                        ->orWhere('passport_number', 'like', "%{$search}%")
                        ->orWhereHas('contact', fn (Builder $contact) => $contact->where('parent_email', 'like', "%{$search}%"));
                });
            })
            ->when($filters['status'] ?? null, fn (Builder $query, string $status) => $query->where('status', $status))
            ->when($filters['payment_status'] ?? null, fn (Builder $query, string $status) => $query->where('payment_status', $status))
            ->when($filters['subject_id'] ?? null, fn (Builder $query, $subjectId) => $query->whereHas('exams', fn (Builder $exam) => $exam->where('ap_exam_subjects.id', $subjectId)))
            ->when($filters['date_from'] ?? null, fn (Builder $query, $date) => $query->whereDate('submitted_at', '>=', $date))
            ->when($filters['date_to'] ?? null, fn (Builder $query, $date) => $query->whereDate('submitted_at', '<=', $date))
            ->when(($filters['period'] ?? null) === 'main', fn (Builder $query) => $query->where('late_fee_total', 0))
            ->when(($filters['period'] ?? null) === 'late', fn (Builder $query) => $query->where('late_fee_total', '>', 0))
            ->when($filters['document_status'] ?? null, fn (Builder $query, string $status) => $query->where('passport_upload_status', $status))
            ->when($filters['verification_status'] ?? null, fn (Builder $query, string $status) => $query->where('verification_status', $status))
            ->when(trim((string) ($filters['school'] ?? '')), fn (Builder $query, string $school) => $query->where('school_name', 'like', '%'.trim($school).'%'))
            ->latest('submitted_at');
    }

    /**
     * @param Collection<int, StudentRegistration> $registrations
     * @param array<string, mixed> $filters
     * @return array<int, array<string, string|int|null>>
     */
    private function rows(Collection $registrations, array $filters, string $template): array
    {
        $includeNotes = (bool) ($filters['include_notes'] ?? false);
        $maskPassport = (bool) ($filters['mask_passport'] ?? true);

        return $registrations->flatMap(function (StudentRegistration $registration) use ($includeNotes, $maskPassport, $template): array {
            $exams = $registration->exams->isEmpty() ? collect([null]) : $registration->exams;

            return $exams->map(function ($exam) use ($registration, $includeNotes, $maskPassport, $template): array {
                $row = [
                    'Registration Number' => $registration->registration_number,
                    'Student Name' => $registration->student_full_name,
                    'Student Email' => $registration->student_email,
                    'Parent Name' => $registration->contact?->parent_full_name,
                    'Parent Email' => $registration->contact?->parent_email,
                    'Passport Number' => $maskPassport ? $this->maskPassport($registration->passport_number) : $registration->passport_number,
                    'School' => $registration->school_name,
                    'Grade' => $registration->grade_level,
                    'Subject Code' => $exam?->code,
                    'Subject Name' => $exam?->name,
                    'Exam Date' => $exam?->pivot?->exam_date ? Carbon::parse($exam->pivot->exam_date)->format('Y-m-d') : null,
                    'Registration Period' => $registration->registration_period,
                    'Registration Status' => $registration->status,
                    'Payment Status' => $registration->payment_status,
                    'Document Status' => $registration->passport_upload_status,
                    'Verification Status' => $registration->verification_status,
                    'Exam Fee' => $exam?->pivot?->exam_fee,
                    'Service Fee' => $exam?->pivot?->service_fee,
                    'Late Fee' => $exam?->pivot?->late_fee_snapshot,
                    'Total Fee' => $registration->total_fee,
                    'Submitted At' => optional($registration->submitted_at)->format('Y-m-d H:i'),
                ];

                if ($template === 'tpca') {
                    $row = array_intersect_key($row, array_flip([
                        'Registration Number', 'Student Name', 'Student Email', 'Passport Number', 'School', 'Grade',
                        'Subject Code', 'Subject Name', 'Exam Date', 'Payment Status', 'Document Status',
                    ]));
                }

                if ($template === 'school') {
                    $row = array_intersect_key($row, array_flip([
                        'School', 'Grade', 'Student Name', 'Student Email', 'Parent Email', 'Subject Name',
                        'Registration Status', 'Payment Status', 'Verification Status',
                    ]));
                }

                if ($includeNotes) {
                    $row['Internal Notes'] = $registration->adminNotes->pluck('note')->implode(' | ');
                }

                return $row;
            })->all();
        })->values()->all();
    }

    /**
     * @param array<int, array<string, string|int|null>> $rows
     */
    private function csv(array $rows): string
    {
        $handle = fopen('php://temp', 'r+');
        $headers = array_keys($rows[0] ?? ['Registration Number' => null]);
        fputcsv($handle, $headers);

        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return (string) $content;
    }

    /**
     * @param array<int, array<string, string|int|null>> $rows
     */
    private function xlsx(array $rows): string
    {
        $headers = array_keys($rows[0] ?? ['Registration Number' => null]);
        $sheetRows = array_merge([$headers], array_map(fn (array $row) => array_values($row), $rows));
        $tempPath = tempnam(sys_get_temp_dir(), 'ap-export-');
        $zip = new ZipArchive();
        $zip->open($tempPath, ZipArchive::OVERWRITE);
        $zip->addFromString('[Content_Types].xml', $this->contentTypesXml());
        $zip->addFromString('_rels/.rels', $this->relsXml());
        $zip->addFromString('xl/workbook.xml', $this->workbookXml());
        $zip->addFromString('xl/_rels/workbook.xml.rels', $this->workbookRelsXml());
        $zip->addFromString('xl/worksheets/sheet1.xml', $this->worksheetXml($sheetRows));
        $zip->close();

        $content = file_get_contents($tempPath);
        @unlink($tempPath);

        return (string) $content;
    }

    private function maskPassport(string $passport): string
    {
        return strlen($passport) <= 4 ? str_repeat('*', strlen($passport)) : str_repeat('*', max(strlen($passport) - 4, 0)).substr($passport, -4);
    }

    /**
     * @param array<int, array<int, mixed>> $rows
     */
    private function worksheetXml(array $rows): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>';

        foreach ($rows as $rowIndex => $row) {
            $xml .= '<row r="'.($rowIndex + 1).'">';
            foreach ($row as $colIndex => $value) {
                $cell = $this->cellName($colIndex, $rowIndex + 1);
                $xml .= '<c r="'.$cell.'" t="inlineStr"><is><t>'.htmlspecialchars((string) $value, ENT_XML1).'</t></is></c>';
            }
            $xml .= '</row>';
        }

        return $xml.'</sheetData></worksheet>';
    }

    private function cellName(int $column, int $row): string
    {
        $name = '';
        $column++;
        while ($column > 0) {
            $mod = ($column - 1) % 26;
            $name = chr(65 + $mod).$name;
            $column = intdiv($column - $mod, 26);
        }

        return $name.$row;
    }

    private function contentTypesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/></Types>';
    }

    private function relsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>';
    }

    private function workbookXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="Registrations" sheetId="1" r:id="rId1"/></sheets></workbook>';
    }

    private function workbookRelsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/></Relationships>';
    }
}
