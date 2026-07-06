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
            ->with(['contact', 'exams', 'practiceExamSelections', 'adminNotes'])
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
            ->when($filters['receipt_status'] ?? null, fn (Builder $query, string $status) => $query->whereHas('receiptRequests', fn (Builder $receipt) => $receipt->where('status', $status)))
            ->when(isset($filters['needs_accommodations']) && $filters['needs_accommodations'] !== '', fn (Builder $query) => $query->where('needs_accommodations', (bool) $filters['needs_accommodations']))
            ->when($filters['accommodation_status'] ?? null, fn (Builder $query, string $status) => $query->where('accommodation_status', $status))
            ->when(isset($filters['preparation_interest']) && $filters['preparation_interest'] !== '', fn (Builder $query) => $query->where('preparation_interest', (bool) $filters['preparation_interest']))
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
                    'English Family Name' => $registration->family_name_en,
                    'English First Name' => $registration->first_name_en,
                    'Middle Initial' => $registration->middle_initial,
                    'Middle Name' => $registration->middle_name,
                    'Chinese Legal Name' => $registration->chinese_legal_name,
                    'Preferred Name' => $registration->preferred_name,
                    'Date of Birth' => optional($registration->date_of_birth)->format('Y-m-d'),
                    'Nationality' => $registration->nationality,
                    'Gender' => $registration->gender,
                    'Student Email' => $registration->student_email,
                    'Student Phone' => $registration->student_phone,
                    'Parent Name' => $registration->contact?->parent_full_name,
                    'Parent First Name' => $registration->contact?->parent_first_name,
                    'Parent Last Name' => $registration->contact?->parent_last_name,
                    'Parent Relationship' => $registration->contact?->relationship,
                    'Parent Email' => $registration->contact?->parent_email,
                    'Parent Phone' => $registration->contact?->parent_phone,
                    'Mailing Address' => $this->mailingAddress($registration),
                    'Emergency Contact' => $registration->contact?->emergency_contact_name,
                    'Emergency Phone' => $registration->contact?->emergency_contact_phone,
                    'Emergency Relationship' => $registration->contact?->emergency_contact_relationship,
                    'Passport Number' => $maskPassport ? $this->maskPassport($registration->passport_number) : $registration->passport_number,
                    'Passport Expiry Date' => optional($registration->passport_expiry_date)->format('Y-m-d'),
                    'School' => $registration->school_name,
                    'School Country' => $registration->school_country,
                    'School City' => $registration->school_city,
                    'Grade' => $registration->grade_level,
                    'Graduation Year' => $registration->graduation_year,
                    'Subject Code' => $exam?->code,
                    'Subject Name' => $exam?->name,
                    'Exam Date' => $exam?->pivot?->exam_date ? Carbon::parse($exam->pivot->exam_date)->format('Y-m-d') : null,
                    'Practice Exams' => $this->practiceExamSummary($registration),
                    'Practice Exam Count' => $registration->practice_exam_count,
                    'Practice Exam Total' => $registration->practice_exam_total,
                    'Needs Accommodations' => $registration->needs_accommodations ? 'Yes' : 'No',
                    'SSD Code' => $registration->ssd_code,
                    'Accommodation Status' => $registration->accommodation_status,
                    'Accommodation Requests' => $this->accommodationSummary($registration),
                    'AP Prep Interest' => $registration->preparation_interest ? 'Yes' : 'No',
                    'Group Class Interest' => $registration->group_class_interest ? 'Yes' : 'No',
                    'Private Tutoring Interest' => $registration->private_tutoring_interest ? 'Yes' : 'No',
                    'Preferred Tutoring Schedule' => $registration->preferred_tutoring_schedule,
                    'Preferred Tutoring Language' => $registration->preferred_tutoring_language,
                    'Preparation Notes' => $registration->preparation_notes,
                    'Registration Period' => $registration->registration_period,
                    'Registration Status' => $registration->status,
                    'Payment Status' => $registration->payment_status,
                    'Payment Method' => $registration->payment_method,
                    'Payment Reference' => $registration->payment_reference,
                    'Payment Amount' => $registration->payment_amount,
                    'Payment Date' => optional($registration->payment_date)->format('Y-m-d H:i'),
                    'Document Status' => $registration->passport_upload_status,
                    'Verification Status' => $registration->verification_status,
                    'Exam Fee' => $exam?->pivot?->exam_fee,
                    'Service Fee' => $exam?->pivot?->service_fee,
                    'Late Fee' => $exam?->pivot?->late_fee_snapshot,
                    'Grand Total' => $registration->grand_total ?: $registration->total_fee,
                    'Submitted At' => optional($registration->submitted_at)->format('Y-m-d H:i'),
                ];

                if ($template === 'tpca') {
                    $row = array_intersect_key($row, array_flip([
                        'Registration Number', 'Student Name', 'English Family Name', 'English First Name', 'Middle Name',
                        'Chinese Legal Name', 'Date of Birth', 'Nationality', 'Student Email', 'Passport Number',
                        'Passport Expiry Date', 'School', 'Grade', 'Subject Code', 'Subject Name', 'Exam Date',
                        'Practice Exams', 'Payment Status', 'Document Status', 'Needs Accommodations',
                        'SSD Code', 'Accommodation Requests', 'AP Prep Interest', 'Group Class Interest',
                        'Private Tutoring Interest', 'Preferred Tutoring Schedule', 'Preferred Tutoring Language',
                    ]));
                }

                if ($template === 'school') {
                    $row = array_intersect_key($row, array_flip([
                        'School', 'School City', 'Grade', 'Student Name', 'Chinese Legal Name', 'Student Email',
                        'Student Phone', 'Parent Name', 'Parent Email', 'Parent Phone', 'Subject Name',
                        'Practice Exams', 'AP Prep Interest', 'Group Class Interest', 'Private Tutoring Interest',
                        'Preferred Tutoring Schedule', 'Preferred Tutoring Language', 'Registration Status',
                        'Payment Status', 'Verification Status',
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

    private function practiceExamSummary(StudentRegistration $registration): string
    {
        return $registration->practiceExamSelections
            ->map(fn ($selection) => $selection->exam_name.' ('.$selection->currency.' '.number_format($selection->practice_fee).')')
            ->implode(' | ');
    }

    private function accommodationSummary(StudentRegistration $registration): string
    {
        return collect($registration->accommodations_payload ?? [])
            ->map(fn (array $row) => trim(($row['exam'] ?? '').': '.($row['request'] ?? ''), ': '))
            ->filter()
            ->implode(' | ');
    }

    private function mailingAddress(StudentRegistration $registration): string
    {
        return collect([
            $registration->contact?->mailing_address,
            $registration->contact?->mailing_city,
            $registration->contact?->postal_code,
        ])->filter()->implode(', ');
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
