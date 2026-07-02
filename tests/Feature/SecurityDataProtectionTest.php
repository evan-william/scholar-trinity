<?php

namespace Tests\Feature;

use App\Models\BackupLog;
use App\Models\ReceiptRequest;
use App\Models\RegistrationPayment;
use App\Models\SecurityAuditLog;
use App\Models\StudentRegistration;
use App\Models\User;
use App\Services\SecurityAuditService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class SecurityDataProtectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_security_headers_and_https_redirect_can_be_enabled(): void
    {
        $this->get('/student-registration')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');

        config(['security.force_https' => true]);
        $this->get('http://example.com/student-registration')->assertRedirect('https://example.com/student-registration');
    }

    public function test_admin_access_and_auth_events_are_audited(): void
    {
        $admin = $this->adminUser();
        $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => 'wrong-password',
        ])->assertSessionHasErrors(['email']);

        $this->assertDatabaseHas('security_audit_logs', [
            'module' => 'auth',
            'event_type' => 'admin_login_failed',
            'status' => 'failed',
        ]);

        $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => 'StrongPass!123',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertDatabaseHas('security_audit_logs', [
            'module' => 'auth',
            'event_type' => 'admin_login_success',
        ]);

        $user = User::query()->create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => Hash::make('StrongPass!123'),
            'is_admin' => false,
        ]);
        $this->actingAs($user)->get('/admin/dashboard')->assertForbidden();
        $this->assertDatabaseHas('security_audit_logs', [
            'event_type' => 'unauthorized_access',
            'status' => 'failed',
        ]);
    }

    public function test_passport_files_are_private_and_download_is_audited(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('student-passports/passport.pdf', 'private-passport');
        [$registration] = $this->registrationAndPayment();
        $registration->update([
            'passport_file_path' => 'student-passports/passport.pdf',
            'passport_original_name' => 'passport.pdf',
            'passport_mime_type' => 'application/pdf',
            'passport_upload_status' => 'uploaded',
        ]);

        $this->get('/storage/student-passports/passport.pdf')->assertStatus(403);
        $this->get(route('admin.student-registrations.passport.download', $registration))->assertRedirect('/login');

        $this->actingAs($this->adminUser('admin2@example.com'))
            ->get(route('admin.student-registrations.passport.download', $registration))
            ->assertOk();

        $this->assertDatabaseHas('security_audit_logs', [
            'module' => 'documents',
            'event_type' => 'passport_downloaded',
        ]);
    }

    public function test_suspicious_and_oversized_uploads_are_rejected(): void
    {
        Storage::fake('local');
        [$registration, $payment] = $this->registrationAndPayment();

        $this->actingAs($this->adminUser())
            ->post(route('admin.student-registrations.passport.replace', $registration), [
                'passport' => UploadedFile::fake()->create('passport.pdf.exe', 10, 'application/pdf'),
                'reason' => 'Testing suspicious filename.',
            ])->assertSessionHasErrors(['passport']);

        config(['security.file_max_kb' => 1]);
        $this->post(route('payments.proof.upload', $payment), [
            'proof' => UploadedFile::fake()->create('proof.pdf', 2, 'application/pdf'),
        ])->assertSessionHasErrors(['proof']);
    }

    public function test_audit_log_masks_sensitive_values_and_admin_filter_works(): void
    {
        app(SecurityAuditService::class)->log('payment', 'gateway_callback_received', 'Gateway callback received.', null, [], [
            'password' => 'secret',
            'hash_key' => 'secret-hash',
            'status' => 'paid',
        ]);

        $log = SecurityAuditLog::query()->firstOrFail();
        $this->assertSame('[masked]', $log->new_values['password']);
        $this->assertSame('[masked]', $log->new_values['hash_key']);
        $this->assertSame('paid', $log->new_values['status']);

        $this->actingAs($this->adminUser())
            ->get(route('admin.security.audit.index', ['module' => 'payment']))
            ->assertOk()
            ->assertSee('gateway_callback_received');
    }

    public function test_receipt_export_and_status_change_are_audited(): void
    {
        [$registration, $payment] = $this->registrationAndPayment();
        $receipt = ReceiptRequest::query()->create([
            'student_registration_id' => $registration->id,
            'registration_payment_id' => $payment->id,
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

        $this->actingAs($admin)
            ->post(route('admin.receipts.status', $receipt), [
                'status' => 'voided',
                'notes' => 'Duplicate request.',
            ])->assertRedirect(route('admin.receipts.show', $receipt));

        $this->actingAs($admin)->get(route('admin.receipts.export'))->assertOk();

        $this->assertDatabaseHas('security_audit_logs', ['event_type' => 'receipt_status_changed']);
        $this->assertDatabaseHas('security_audit_logs', ['event_type' => 'receipt_exported']);
    }

    public function test_backup_command_runs_and_logs_result(): void
    {
        $this->artisan('security:backup-database')->assertExitCode(0);

        $this->assertDatabaseHas('backup_logs', [
            'backup_type' => 'database',
            'status' => 'completed',
        ]);
    }

    public function test_storage_backup_command_creates_private_manifest_and_log(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('student-passports/passport.pdf', 'private-passport');
        Storage::disk('local')->put('payment-proofs/proof.pdf', 'private-proof');

        $this->artisan('security:backup-storage')->assertExitCode(0);

        $log = BackupLog::query()->where('backup_type', 'storage_manifest')->firstOrFail();
        $this->assertSame('completed', $log->status);
        Storage::disk('local')->assertExists($log->path);
        $manifest = json_decode(Storage::disk('local')->get($log->path), true);
        $this->assertSame(2, $manifest['file_count']);
    }

    private function registrationAndPayment(): array
    {
        $registration = StudentRegistration::query()->create([
            'registration_number' => 'APR-2026-'.str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT),
            'status' => 'submitted',
            'payment_status' => 'paid',
            'student_full_name' => 'Alex Chen',
            'date_of_birth' => '2009-01-15',
            'nationality' => 'Taiwan',
            'passport_number' => 'A'.random_int(10000000, 99999999),
            'student_email' => Str::lower(Str::random(6)).'@example.com',
            'school_name' => 'Taipei International School',
            'school_country' => 'Taiwan',
            'grade_level' => '11',
            'exam_fee_total' => 7800,
            'service_fee_total' => 1200,
            'late_fee_total' => 0,
            'total_fee' => 9000,
            'grand_total' => 9000,
            'currency' => 'NTD',
            'fee_snapshot_at' => now(),
            'submitted_at' => now(),
        ]);
        $registration->contact()->create([
            'parent_full_name' => 'Ivon Jou',
            'relationship' => 'Mother',
            'parent_email' => Str::lower(Str::random(6)).'@example.com',
            'parent_phone' => '+886 987 654 321',
            'emergency_contact_name' => 'Mark Jou',
            'emergency_contact_phone' => '+886 988 111 222',
            'emergency_contact_relationship' => 'Father',
        ]);
        $payment = RegistrationPayment::query()->create([
            'student_registration_id' => $registration->id,
            'payment_reference' => $registration->registration_number.'-PAY',
            'provider' => 'manual',
            'payment_method' => 'manual_bank_transfer',
            'payment_status' => 'paid',
            'exam_fee_amount' => 7800,
            'service_fee_amount' => 1200,
            'late_fee_amount' => 0,
            'grand_total' => 9000,
            'currency' => 'NTD',
            'paid_at' => now(),
        ]);

        return [$registration->fresh(['contact']), $payment->fresh('registration')];
    }

    private function adminUser(string $email = 'admin@example.com'): User
    {
        return User::query()->create([
            'name' => 'Admin',
            'email' => $email,
            'password' => Hash::make('StrongPass!123'),
            'is_admin' => true,
            'password_changed_at' => now(),
        ]);
    }
}
