<?php

namespace Tests\Feature;

use App\Mail\PassportReuploadRequested;
use App\Mail\RegistrationCompletedMail;
use App\Models\ApExamSubject;
use App\Models\ReceiptRequest;
use App\Models\RegistrationExportLog;
use App\Models\StudentRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class PassportAndExportManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_passport_file_is_admin_only_and_audited_for_preview_and_download(): void
    {
        Storage::fake('local');
        [$registration] = $this->registrationWithPassport();

        $this->get(route('admin.student-registrations.passport.preview', $registration))
            ->assertRedirect('/login');

        $this->actingAs($this->adminUser())
            ->get(route('admin.student-registrations.passport.preview', $registration))
            ->assertOk();

        $this->actingAs($this->adminUser('admin2@example.com'))
            ->get(route('admin.student-registrations.passport.download', $registration))
            ->assertOk();

        $registration->refresh();
        $this->assertNotNull($registration->passport_last_viewed_at);
        $this->assertNotNull($registration->passport_last_downloaded_at);
        $this->assertDatabaseHas('registration_audit_logs', [
            'student_registration_id' => $registration->id,
            'action' => 'passport_viewed',
        ]);
        $this->assertDatabaseHas('registration_audit_logs', [
            'student_registration_id' => $registration->id,
            'action' => 'passport_downloaded',
        ]);
    }

    public function test_admin_can_download_filtered_passport_zip(): void
    {
        if (! class_exists(\ZipArchive::class)) {
            $this->markTestSkipped('ZipArchive extension is required for passport ZIP downloads.');
        }

        Storage::fake('local');
        [$registration] = $this->registrationWithPassport();
        $admin = $this->adminUser();

        $this->actingAs($admin)
            ->get(route('admin.student-registrations.passport.zip', ['search' => $registration->registration_number]))
            ->assertOk();

        $registration->refresh();
        $this->assertNotNull($registration->passport_last_downloaded_at);
        $this->assertSame($admin->id, $registration->passport_last_downloaded_by);
    }

    public function test_admin_can_replace_mark_invalid_and_request_passport_reupload(): void
    {
        Storage::fake('local');
        Mail::fake();
        [$registration] = $this->registrationWithPassport();
        $admin = $this->adminUser();

        $this->actingAs($admin)
            ->post(route('admin.student-registrations.passport.replace', $registration), [
                'passport' => UploadedFile::fake()->image('new-passport.jpg'),
                'reason' => 'Clearer passport scan received.',
            ])->assertRedirect(route('admin.student-registrations.show', $registration));

        $registration->refresh();
        Storage::disk('local')->assertExists($registration->passport_file_path);
        $this->assertSame('pending_review', $registration->passport_upload_status);
        $this->assertSame('Clearer passport scan received.', $registration->passport_replacement_reason);

        $this->actingAs($admin)
            ->post(route('admin.student-registrations.passport.status', $registration), [
                'status' => 'invalid',
            ])->assertSessionHasErrors(['invalid_reason']);

        $this->actingAs($admin)
            ->post(route('admin.student-registrations.passport.status', $registration), [
                'status' => 'invalid',
                'invalid_reason' => 'Name does not match registration.',
            ])->assertRedirect(route('admin.student-registrations.show', $registration));

        $this->assertSame('invalid', $registration->fresh()->passport_upload_status);

        $deadline = now()->addWeek()->format('Y-m-d');
        $this->actingAs($admin)
            ->post(route('admin.student-registrations.passport.reupload', $registration), [
                'reason' => 'Upload passport photo page only.',
                'deadline' => $deadline,
            ])->assertRedirect(route('admin.student-registrations.show', $registration));

        $this->assertSame('reupload_requested', $registration->fresh()->passport_upload_status);
        Mail::assertSent(PassportReuploadRequested::class);
        $this->assertDatabaseHas('registration_audit_logs', [
            'student_registration_id' => $registration->id,
            'action' => 'passport_reupload_requested',
        ]);
    }

    public function test_export_creates_private_history_file_without_passport_file_path(): void
    {
        Storage::fake('local');
        [$registration, $subject] = $this->registrationWithPassport([
            'payment_status' => 'paid',
            'verification_status' => 'verified',
            'needs_accommodations' => true,
            'accommodation_status' => 'approved',
        ]);
        ReceiptRequest::query()->create([
            'student_registration_id' => $registration->id,
            'buyer_name' => 'Ivon Jou',
            'buyer_email' => 'ivon@example.com',
            'buyer_phone' => '+886 987 654 321',
            'receipt_type' => 'personal',
            'service_fee_amount' => 1200,
            'taxable_receipt_amount' => 1200,
            'non_receipt_amount' => 7800,
            'currency' => 'NTD',
            'status' => 'pending_issue',
        ]);
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)->get(route('admin.student-registrations.export', [
            'format' => 'csv',
            'template' => 'tpca',
            'subject_id' => $subject->id,
            'payment_status' => 'paid',
            'document_status' => 'uploaded',
            'receipt_status' => 'pending_issue',
            'needs_accommodations' => '1',
            'accommodation_status' => 'approved',
            'mask_passport' => '1',
        ]));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/csv; charset=utf-8');

        $export = RegistrationExportLog::query()->firstOrFail();
        $this->assertSame('tpca', $export->export_type);
        $this->assertSame(1, $export->record_count);
        Storage::disk('local')->assertExists($export->storage_path);
        $content = Storage::disk('local')->get($export->storage_path);
        $this->assertStringContainsString('Registration Number', $content);
        $this->assertStringContainsString('****5678', $content);
        $this->assertStringContainsString('Practice Exams', $content);
        $this->assertStringContainsString('Needs Accommodations', $content);
        $this->assertStringNotContainsString($registration->passport_file_path, $content);

        $this->actingAs($admin)
            ->get(route('admin.exports.index'))
            ->assertOk()
            ->assertSee($export->file_name);

        $this->actingAs($admin)
            ->get(route('admin.exports.download', $export))
            ->assertOk();
    }

    public function test_xlsx_export_is_generated(): void
    {
        Storage::fake('local');
        $this->registrationWithPassport();

        $this->actingAs($this->adminUser())
            ->get(route('admin.student-registrations.export', ['format' => 'xlsx']))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $this->assertSame('xlsx', RegistrationExportLog::query()->firstOrFail()->export_format);
    }

    public function test_paid_and_verified_registration_is_marked_completed_and_emails_student(): void
    {
        Mail::fake();
        [$registration] = $this->registrationWithPassport([
            'payment_status' => 'paid',
            'status' => 'paid',
        ]);

        $this->actingAs($this->adminUser())
            ->post(route('admin.student-registrations.verify', $registration), [
                'verification_status' => 'verified',
                'verification_note' => 'Payment and documents checked.',
            ])->assertRedirect(route('admin.student-registrations.show', $registration));

        $this->assertSame('completed', $registration->fresh()->status);
        Mail::assertSent(RegistrationCompletedMail::class);
    }

    /**
     * @return array{0: StudentRegistration, 1: ApExamSubject}
     */
    private function registrationWithPassport(array $overrides = []): array
    {
        $subject = ApExamSubject::query()->create([
            'name' => 'Calculus AB',
            'code' => 'CALAB'.Str::random(5),
            'category' => 'Mathematics',
            'exam_date' => '2027-05-04',
            'exam_fee' => 7800,
            'service_fee' => 1200,
            'late_registration_fee' => 1500,
            'currency' => 'NTD',
            'status' => 'open',
            'registration_open_at' => now()->subMonth(),
            'registration_close_at' => now()->addMonth(),
            'is_active' => true,
        ]);

        Storage::disk('local')->put('student-passports/original.pdf', 'passport-data');

        $registration = StudentRegistration::query()->create(array_replace([
            'registration_number' => 'APR-2026-'.str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT),
            'status' => 'submitted',
            'registration_period' => 'main',
            'payment_status' => 'unpaid',
            'student_full_name' => 'Alex Chen',
            'date_of_birth' => '2009-01-15',
            'nationality' => 'Taiwan',
            'passport_number' => 'A12345678',
            'passport_upload_status' => 'uploaded',
            'passport_document_uuid' => (string) Str::uuid(),
            'passport_file_path' => 'student-passports/original.pdf',
            'passport_original_name' => 'original.pdf',
            'passport_mime_type' => 'application/pdf',
            'passport_file_size' => 13,
            'passport_uploaded_at' => now(),
            'student_email' => 'alex@example.com',
            'student_phone' => '+886 912 345 678',
            'school_name' => 'Taipei International School',
            'school_country' => 'Taiwan',
            'grade_level' => '11',
            'exam_fee_total' => 7800,
            'service_fee_total' => 1200,
            'late_fee_total' => 0,
            'total_fee' => 9000,
            'submitted_at' => now(),
        ], $overrides));

        $registration->contact()->create([
            'parent_full_name' => 'Ivon Jou',
            'relationship' => 'Mother',
            'parent_email' => 'parent@example.com',
            'parent_phone' => '+886 987 654 321',
            'emergency_contact_name' => 'Mark Jou',
            'emergency_contact_phone' => '+886 988 111 222',
            'emergency_contact_relationship' => 'Father',
        ]);

        $registration->exams()->attach($subject->id, [
            'uuid' => (string) Str::uuid(),
            'subject_name' => $subject->name,
            'exam_date' => $subject->exam_date,
            'exam_fee' => 7800,
            'service_fee' => 1200,
            'late_fee_snapshot' => 0,
            'total_amount_snapshot' => 9000,
            'currency_snapshot' => 'NTD',
            'selected_at' => now(),
            'status' => 'selected',
        ]);

        return [$registration->fresh(['contact', 'exams']), $subject];
    }

    private function adminUser(string $email = 'admin@example.com'): User
    {
        return User::query()->create([
            'name' => 'Admin',
            'email' => $email,
            'password' => Hash::make('StrongPass!123'),
            'is_admin' => true,
        ]);
    }
}
