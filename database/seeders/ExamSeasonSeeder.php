<?php

namespace Database\Seeders;

use App\Models\ExamSeason;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ExamSeasonSeeder extends Seeder
{
    public function run(): void
    {
        ExamSeason::query()->updateOrCreate(
            ['exam_year' => 2027],
            [
                'uuid' => (string) Str::uuid(),
                'season_name' => 'AP Exam 2027',
                'academic_year' => '2026-2027',
                'main_registration_start_at' => now()->subMonth(),
                'main_registration_end_at' => now()->addMonths(3),
                'late_registration_start_at' => now()->addMonths(3)->addDay(),
                'late_registration_end_at' => now()->addMonths(5),
                'timezone' => 'Asia/Taipei',
                'currency' => 'NTD',
                'default_service_fee' => 1200,
                'default_late_fee' => 1500,
                'status' => 'open',
                'is_active' => true,
                'public_status_message' => 'AP Exam registration is open.',
            ]
        );
    }
}
