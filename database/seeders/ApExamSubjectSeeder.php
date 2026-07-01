<?php

namespace Database\Seeders;

use App\Models\ApExamSubject;
use App\Models\ExamSeason;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ApExamSubjectSeeder extends Seeder
{
    public function run(): void
    {
        $season = ExamSeason::query()->where('is_active', true)->first();
        $subjects = [
            ['name' => 'Biology', 'code' => 'BIO', 'exam_date' => '2027-05-10'],
            ['name' => 'Chemistry', 'code' => 'CHEM', 'exam_date' => '2027-05-11'],
            ['name' => 'Physics 1', 'code' => 'PHY1', 'exam_date' => '2027-05-13'],
            ['name' => 'Calculus AB', 'code' => 'CALAB', 'exam_date' => '2027-05-04'],
            ['name' => 'Calculus BC', 'code' => 'CALBC', 'exam_date' => '2027-05-04'],
            ['name' => 'Statistics', 'code' => 'STAT', 'exam_date' => '2027-05-06'],
            ['name' => 'Computer Science A', 'code' => 'CSA', 'exam_date' => '2027-05-07'],
            ['name' => 'English Language and Composition', 'code' => 'ENGLANG', 'exam_date' => '2027-05-12'],
            ['name' => 'Macroeconomics', 'code' => 'MACRO', 'exam_date' => '2027-05-14'],
            ['name' => 'Psychology', 'code' => 'PSY', 'exam_date' => '2027-05-15'],
            ['name' => 'Chinese Language and Culture', 'code' => 'CHN', 'exam_date' => '2027-05-16'],
        ];

        foreach ($subjects as $index => $subject) {
            $model = ApExamSubject::query()->updateOrCreate(
                ['code' => $subject['code']],
                $subject + [
                    'exam_season_id' => $season?->id,
                    'category' => in_array($subject['code'], ['CALAB', 'CALBC', 'STAT'], true) ? 'Mathematics' : (in_array($subject['code'], ['BIO', 'CHEM', 'PHY1'], true) ? 'Sciences' : 'General'),
                    'description' => 'Official AP exam registration subject.',
                    'start_time' => '08:00',
                    'end_time' => '12:00',
                    'timezone' => 'Asia/Taipei',
                    'location' => 'TPCA Campus',
                    'quota' => 50,
                    'exam_fee' => 7800,
                    'service_fee' => 1200,
                    'late_registration_fee' => 1500,
                    'currency' => 'NTD',
                    'status' => 'open',
                    'registration_open_at' => now()->subMonth(),
                    'registration_close_at' => now()->addMonths(6),
                    'late_registration_start_at' => now()->addMonths(3),
                    'late_registration_end_at' => now()->addMonths(5),
                    'sort_order' => $index,
                    'is_active' => true,
                ]
            );

            if (! $model->uuid) {
                $model->forceFill(['uuid' => (string) Str::uuid()])->save();
            }
        }
    }
}
