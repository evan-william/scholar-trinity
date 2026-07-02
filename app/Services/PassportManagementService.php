<?php

namespace App\Services;

use App\Mail\PassportReuploadRequested;
use App\Models\RegistrationAuditLog;
use App\Models\StudentRegistration;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PassportManagementService
{
    public function preview(StudentRegistration $registration, int $adminId): StreamedResponse
    {
        $this->ensureFileExists($registration);
        $registration->update([
            'passport_last_viewed_at' => now(),
            'passport_last_viewed_by' => $adminId,
        ]);
        $this->audit($registration, 'passport_viewed', null, null, null, $adminId);
        app(SecurityAuditService::class)->log('documents', 'passport_viewed', 'Passport viewed.', $registration, [], [], ['registration' => $registration->registration_number]);
        Log::info('Passport viewed.', ['registration' => $registration->registration_number, 'admin_id' => $adminId]);

        $fileName = $this->safeFileName($registration->passport_original_name ?: 'passport');

        return Storage::disk('local')->response($registration->passport_file_path, $fileName, [
            'Content-Type' => $registration->passport_mime_type ?: 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="'.$fileName.'"',
        ]);
    }

    public function download(StudentRegistration $registration, int $adminId): StreamedResponse
    {
        $this->ensureFileExists($registration);
        $registration->update([
            'passport_last_downloaded_at' => now(),
            'passport_last_downloaded_by' => $adminId,
        ]);
        $this->audit($registration, 'passport_downloaded', null, null, null, $adminId);
        app(SecurityAuditService::class)->log('documents', 'passport_downloaded', 'Passport downloaded.', $registration, [], [], ['registration' => $registration->registration_number]);
        Log::info('Passport downloaded.', ['registration' => $registration->registration_number, 'admin_id' => $adminId]);

        return Storage::disk('local')->download($registration->passport_file_path, $this->safeFileName($registration->passport_original_name ?: 'passport'));
    }

    public function replace(StudentRegistration $registration, UploadedFile $file, string $reason, int $adminId): StudentRegistration
    {
        app(FileSecurityService::class)->validate($file, 'passport');
        $oldPath = $registration->passport_file_path;
        $path = $file->store('student-passports', 'local');

        $registration->update([
            'passport_document_uuid' => $registration->passport_document_uuid ?: (string) Str::uuid(),
            'passport_file_path' => $path,
            'passport_original_name' => basename($file->getClientOriginalName()),
            'passport_mime_type' => $file->getMimeType(),
            'passport_file_size' => $file->getSize(),
            'passport_uploaded_at' => now(),
            'passport_uploaded_by' => $adminId,
            'passport_replaced_at' => now(),
            'passport_replaced_by' => $adminId,
            'passport_replacement_reason' => $reason,
            'passport_upload_status' => 'pending_review',
        ]);

        $this->audit($registration, 'passport_replaced', 'passport_file_path', $oldPath, $path, $adminId, $reason);
        app(SecurityAuditService::class)->log('documents', 'passport_replaced', 'Passport replaced.', $registration, ['passport_file_path' => $oldPath], ['passport_file_path' => $path], ['reason' => $reason]);
        Log::info('Passport replaced.', ['registration' => $registration->registration_number, 'admin_id' => $adminId]);

        return $registration->fresh();
    }

    public function mark(StudentRegistration $registration, array $data, int $adminId): StudentRegistration
    {
        if ($data['status'] === 'verified') {
            $registration->update([
                'passport_upload_status' => 'verified',
                'passport_verified_at' => now(),
                'passport_verified_by' => $adminId,
                'passport_verification_note' => $data['verification_note'] ?? null,
            ]);
            $this->audit($registration, 'passport_marked_valid', 'passport_upload_status', null, 'verified', $adminId, $data['verification_note'] ?? null);
            app(SecurityAuditService::class)->log('documents', 'passport_marked_valid', 'Passport marked valid.', $registration, [], ['passport_upload_status' => 'verified']);
        } else {
            $registration->update([
                'passport_upload_status' => 'invalid',
                'passport_invalid_at' => now(),
                'passport_invalid_by' => $adminId,
                'passport_invalid_reason' => $data['invalid_reason'],
            ]);
            $this->audit($registration, 'passport_marked_invalid', 'passport_upload_status', null, 'invalid', $adminId, $data['invalid_reason']);
            app(SecurityAuditService::class)->log('documents', 'passport_marked_invalid', 'Passport marked invalid.', $registration, [], ['passport_upload_status' => 'invalid'], ['reason' => $data['invalid_reason']]);
        }

        Log::info('Passport status updated.', ['registration' => $registration->registration_number, 'status' => $data['status']]);

        return $registration->fresh();
    }

    public function requestReupload(StudentRegistration $registration, array $data, int $adminId): StudentRegistration
    {
        $registration->update([
            'passport_upload_status' => 'reupload_requested',
            'passport_reupload_requested_at' => now(),
            'passport_reupload_deadline_at' => $data['deadline'],
            'passport_reupload_reason' => $data['reason'],
        ]);
        $this->audit($registration, 'passport_reupload_requested', 'passport_upload_status', null, 'reupload_requested', $adminId, $data['reason']);
        app(SecurityAuditService::class)->log('documents', 'passport_reupload_requested', 'Passport re-upload requested.', $registration, [], ['passport_upload_status' => 'reupload_requested'], ['reason' => $data['reason']]);

        Mail::to($registration->student_email)
            ->cc($registration->contact?->parent_email)
            ->send(new PassportReuploadRequested($registration->load('contact')));
        Log::info('Passport re-upload requested.', ['registration' => $registration->registration_number, 'admin_id' => $adminId]);

        return $registration->fresh();
    }

    private function ensureFileExists(StudentRegistration $registration): void
    {
        abort_unless($registration->passport_file_path && Storage::disk('local')->exists($registration->passport_file_path), 404);
    }

    private function safeFileName(string $name): string
    {
        $name = basename(str_replace(["\r", "\n", '"', '\\'], '', $name));
        $name = preg_replace('/[^A-Za-z0-9._ -]/', '_', $name) ?: 'download';

        return trim($name) !== '' ? $name : 'download';
    }

    private function audit(StudentRegistration $registration, string $action, ?string $field, mixed $old, mixed $new, int $adminId, ?string $reason = null): void
    {
        RegistrationAuditLog::query()->create([
            'student_registration_id' => $registration->id,
            'action' => $action,
            'field_name' => $field,
            'old_value' => $old,
            'new_value' => $new,
            'reason' => $reason,
            'performed_by' => $adminId,
            'performed_ip' => request()?->ip(),
            'performed_at' => now(),
        ]);
    }
}
