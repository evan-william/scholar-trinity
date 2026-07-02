<?php

namespace Tests\Feature;

use App\Models\ApExamSubject;
use App\Models\ExamSeason;
use App\Models\SecurityAuditLog;
use App\Models\User;
use App\Repositories\StudentRegistrationRepository;
use App\Services\AnnualReportService;
use App\Services\ExamSeasonService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnnualFutureUseTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_activate_archive_and_duplicate_exam_season(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $source = ExamSeason::query()->create([
            'season_name' => 'AP Exam 2027',
            'academic_year' => '2026-2027',
            'exam_year' => 2027,
            'main_registration_start_at' => now()->subDay(),
            'main_registration_end_at' => now()->addMonth(),
            'late_registration_start_at' => now()->addMonth()->addDay(),
            'late_registration_end_at' => now()->addMonths(2),
            'timezone' => 'Asia/Taipei',
            'currency' => 'NTD',
            'default_service_fee' => 1200,
            'default_late_fee' => 1500,
            'status' => 'open',
            'is_active' => true,
        ]);
        ApExamSubject::query()->create([
            'exam_season_id' => $source->id,
            'name' => 'Biology',
            'code' => 'BIO',
            'category' => 'Sciences',
            'timezone' => 'Asia/Taipei',
            'exam_fee' => 7800,
            'service_fee' => 1200,
            'late_registration_fee' => 1500,
            'currency' => 'NTD',
            'status' => 'open',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.exam-seasons.duplicate', $source), [
            'season_name' => 'AP Exam 2028',
            'academic_year' => '2027-2028',
            'exam_year' => 2028,
        ]);

        $response->assertRedirect();
        $newSeason = ExamSeason::query()->where('exam_year', 2028)->firstOrFail();
        $this->assertSame(1, $newSeason->subjects()->count());
        $this->assertSame(0, $newSeason->registrations()->count());

        $this->actingAs($admin)->post(route('admin.exam-seasons.activate', $newSeason))->assertRedirect();
        $this->assertTrue($newSeason->fresh()->is_active);
        $this->assertFalse($source->fresh()->is_active);

        $this->actingAs($admin)->post(route('admin.exam-seasons.archive', $newSeason), ['close_reason' => 'Finished'])->assertRedirect();
        $this->assertSame('archived', $newSeason->fresh()->status);
        $this->assertNotNull($newSeason->fresh()->archived_at);
        $this->assertGreaterThanOrEqual(3, SecurityAuditLog::query()->where('module', 'ap_registration')->count());
    }

    public function test_season_period_controls_selection_and_late_fee(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $season = ExamSeason::query()->create([
            'season_name' => 'Late Season',
            'academic_year' => '2026-2027',
            'exam_year' => 2027,
            'main_registration_start_at' => now()->subMonths(3),
            'main_registration_end_at' => now()->subMonth(),
            'late_registration_start_at' => now()->subDay(),
            'late_registration_end_at' => now()->addMonth(),
            'timezone' => 'Asia/Taipei',
            'currency' => 'NTD',
            'default_service_fee' => 1200,
            'default_late_fee' => 1500,
            'status' => 'open',
            'is_active' => true,
        ]);
        $subject = ApExamSubject::query()->create([
            'exam_season_id' => $season->id,
            'name' => 'Chemistry',
            'code' => 'CHEM',
            'category' => 'Sciences',
            'timezone' => 'Asia/Taipei',
            'exam_fee' => 7800,
            'service_fee' => 1200,
            'late_registration_fee' => 1500,
            'currency' => 'NTD',
            'status' => 'open',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->assertTrue($subject->isSelectable());
        $this->assertTrue($subject->lateFeeApplies());
        $this->assertSame('late', $season->currentPeriod());

        app(ExamSeasonService::class)->archive($season, $admin->id, 'Closed');

        $this->assertFalse($subject->fresh('examSeason')->isSelectable());
    }

    public function test_repository_filters_public_subjects_to_active_season(): void
    {
        $active = ExamSeason::query()->create([
            'season_name' => 'Active',
            'academic_year' => '2026-2027',
            'exam_year' => 2027,
            'main_registration_start_at' => now()->subDay(),
            'main_registration_end_at' => now()->addDay(),
            'timezone' => 'Asia/Taipei',
            'currency' => 'NTD',
            'status' => 'open',
            'is_active' => true,
        ]);
        $old = ExamSeason::query()->create([
            'season_name' => 'Old',
            'academic_year' => '2025-2026',
            'exam_year' => 2026,
            'timezone' => 'Asia/Taipei',
            'currency' => 'NTD',
            'status' => 'archived',
            'is_active' => false,
            'archived_at' => now(),
        ]);
        $this->makeSubject($active->id, 'ACTIVE');
        $this->makeSubject($old->id, 'OLD');

        $subjects = app(StudentRegistrationRepository::class)->availableSubjects();

        $this->assertSame(['ACTIVE'], $subjects->pluck('code')->all());
    }

    public function test_annual_report_returns_revenue_and_registration_totals(): void
    {
        $season = ExamSeason::query()->create([
            'season_name' => 'AP Exam 2027',
            'academic_year' => '2026-2027',
            'exam_year' => 2027,
            'timezone' => 'Asia/Taipei',
            'currency' => 'NTD',
            'status' => 'open',
            'is_active' => true,
        ]);

        \App\Models\StudentRegistration::query()->create([
            'exam_season_id' => $season->id,
            'registration_number' => 'APR-2027-000001',
            'status' => 'submitted',
            'registration_period_type' => 'main',
            'student_full_name' => 'Test Student',
            'date_of_birth' => '2010-01-01',
            'nationality' => 'Taiwan',
            'passport_number' => 'A1234567',
            'student_email' => 'student@example.com',
            'school_name' => 'Test School',
            'school_country' => 'Taiwan',
            'grade_level' => '11',
            'exam_fee_total' => 7800,
            'service_fee_total' => 1200,
            'late_fee_total' => 0,
            'total_fee' => 9000,
            'grand_total' => 9000,
            'currency' => 'NTD',
            'submitted_at' => now(),
        ]);

        $report = app(AnnualReportService::class)->build($season);

        $this->assertSame(1, $report['registration']['total']);
        $this->assertSame(9000, $report['revenue']['grand_total']);
        $this->assertSame(1200, $report['revenue']['service_fee']);
    }

    public function test_annual_report_can_be_exported_as_csv(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $season = ExamSeason::query()->create([
            'season_name' => 'AP Exam 2027',
            'academic_year' => '2026-2027',
            'exam_year' => 2027,
            'timezone' => 'Asia/Taipei',
            'currency' => 'NTD',
            'status' => 'open',
            'is_active' => true,
        ]);

        \App\Models\StudentRegistration::query()->create([
            'exam_season_id' => $season->id,
            'registration_number' => 'APR-2027-000002',
            'status' => 'completed',
            'registration_period_type' => 'late',
            'payment_status' => 'paid',
            'verification_status' => 'verified',
            'student_full_name' => 'Export Student',
            'date_of_birth' => '2010-01-01',
            'nationality' => 'Taiwan',
            'passport_number' => 'B1234567',
            'student_email' => 'export@example.com',
            'school_name' => 'Test School',
            'school_country' => 'Taiwan',
            'grade_level' => '11',
            'exam_fee_total' => 7800,
            'service_fee_total' => 1200,
            'late_fee_total' => 1500,
            'practice_exam_count' => 1,
            'practice_exam_total' => 1800,
            'needs_accommodations' => true,
            'total_fee' => 12300,
            'grand_total' => 12300,
            'currency' => 'NTD',
            'submitted_at' => now(),
        ]);

        $rows = app(AnnualReportService::class)->csvRows($season);
        $this->assertContains(['registration', 'practice_exam_count', 1, null, $season->season_name], $rows);
        $this->assertContains(['revenue', 'practice_exam', null, 1800, 'NTD'], $rows);

        $this->actingAs($admin)
            ->get(route('admin.reports.annual.export', ['season' => $season->uuid]))
            ->assertOk()
            ->assertHeader('Content-Type', 'text/csv; charset=utf-8');
    }

    private function makeSubject(int $seasonId, string $code): ApExamSubject
    {
        return ApExamSubject::query()->create([
            'exam_season_id' => $seasonId,
            'name' => $code.' Subject',
            'code' => $code,
            'category' => 'General',
            'timezone' => 'Asia/Taipei',
            'exam_fee' => 7800,
            'service_fee' => 1200,
            'late_registration_fee' => 1500,
            'currency' => 'NTD',
            'status' => 'open',
            'sort_order' => 1,
            'is_active' => true,
        ]);
    }
}
