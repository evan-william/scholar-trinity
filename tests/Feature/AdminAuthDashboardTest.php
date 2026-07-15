<?php

namespace Tests\Feature;

use App\Models\ApExamSubject;
use App\Models\StudentRegistration;
use App\Models\User;
use Database\Seeders\ApExamSubjectSeeder;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class AdminAuthDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_login_page_and_login(): void
    {
        $admin = $this->adminUser();

        $this->get('/admin/login')->assertOk()->assertSee('Admin Login');

        $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => 'StrongPass!123',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_admin_entry_route_and_username_alias_open_the_dashboard(): void
    {
        config([
            'admin.login_username' => 'admin',
            'admin.login_email' => 'admin@example.com',
        ]);
        $admin = $this->adminUser();

        $this->get('/admin')->assertRedirect(route('admin.login'));

        $this->post('/admin/login', [
            'login' => 'admin',
            'password' => 'StrongPass!123',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);
        $this->get('/admin')->assertRedirect(route('admin.dashboard'));
    }

    public function test_invalid_login_is_rejected_and_rate_limited(): void
    {
        $this->adminUser();

        $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'wrong-password',
        ])->assertSessionHasErrors(['email']);

        for ($i = 0; $i < 5; $i++) {
            $this->post('/admin/login', [
                'email' => 'admin@example.com',
                'password' => 'wrong-password',
            ]);
        }

        $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'wrong-password',
        ])->assertTooManyRequests();
    }

    public function test_password_reset_email_can_be_requested_and_token_resets_password(): void
    {
        Notification::fake();
        $admin = $this->adminUser();

        $this->post('/admin/forgot-password', ['email' => $admin->email])
            ->assertSessionHas('status');

        Notification::assertSentTo($admin, ResetPassword::class);

        $token = Password::createToken($admin);

        $this->post('/admin/reset-password', [
            'token' => $token,
            'email' => $admin->email,
            'password' => 'NewStrong!1234',
            'password_confirmation' => 'NewStrong!1234',
        ])->assertRedirect(route('admin.login'));

        $this->assertTrue(Hash::check('NewStrong!1234', $admin->fresh()->password));

        $this->post('/admin/reset-password', [
            'token' => 'invalid-token',
            'email' => $admin->email,
            'password' => 'AnotherStrong!1234',
            'password_confirmation' => 'AnotherStrong!1234',
        ])->assertSessionHasErrors(['email']);
    }

    public function test_password_is_hashed_for_admin_user(): void
    {
        $admin = $this->adminUser();

        $this->assertNotSame('StrongPass!123', $admin->password);
        $this->assertTrue(Hash::check('StrongPass!123', $admin->password));
    }

    public function test_dashboard_requires_authentication_and_admin_permission(): void
    {
        $this->get('/admin/dashboard')->assertRedirect(route('login'));

        $user = User::query()->create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('StrongPass!123'),
            'is_admin' => false,
        ]);

        $this->actingAs($user)->get('/admin/dashboard')->assertForbidden();
    }

    public function test_admin_session_timeout_logs_out_user(): void
    {
        config(['admin.session_timeout_minutes' => 30]);
        $admin = $this->adminUser();

        $this->actingAs($admin)
            ->withSession(['admin_last_activity_at' => now()->subMinutes(31)])
            ->get('/admin/dashboard')
            ->assertRedirect(route('admin.login'));

        $this->assertGuest();
    }

    public function test_dashboard_counts_and_subject_summary_are_correct_without_passport_data(): void
    {
        $this->seed(ApExamSubjectSeeder::class);
        $subject = ApExamSubject::query()->firstOrFail();
        $registration = StudentRegistration::query()->create([
            'registration_number' => 'APR-2026-000001',
            'status' => 'paid',
            'student_full_name' => 'Alex Chen',
            'date_of_birth' => '2009-01-15',
            'nationality' => 'Taiwan',
            'passport_number' => 'A12345678',
            'student_email' => 'alex@example.com',
            'school_name' => 'Taipei International School',
            'school_country' => 'Taiwan',
            'grade_level' => '11',
            'exam_fee_total' => 7800,
            'service_fee_total' => 1200,
            'late_fee_total' => 1500,
            'total_fee' => 10500,
            'submitted_at' => now(),
        ]);
        $registration->exams()->attach($subject->id, [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'subject_name' => $subject->name,
            'exam_date' => $subject->exam_date,
            'exam_fee' => 7800,
            'service_fee' => 1200,
            'late_fee_snapshot' => 1500,
            'total_amount_snapshot' => 10500,
            'currency_snapshot' => 'NTD',
            'selected_at' => now(),
            'status' => 'selected',
        ]);

        $this->actingAs($this->adminUser())
            ->get('/admin/dashboard?period=late')
            ->assertOk()
            ->assertSee('Total Registrations')
            ->assertSee('NT$ 10,500')
            ->assertSee($subject->name)
            ->assertDontSee('passport_path');
    }

    private function adminUser(): User
    {
        return User::query()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('StrongPass!123'),
            'is_admin' => true,
            'password_changed_at' => now(),
        ]);
    }
}
