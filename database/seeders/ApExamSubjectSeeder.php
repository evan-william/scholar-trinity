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
            ['name' => 'Biology', 'code' => 'BIO', 'exam_date' => '2027-05-03', 'start_time' => '13:00'],
            ['name' => 'Chemistry', 'code' => 'CHEM', 'exam_date' => '2027-05-06', 'start_time' => '13:00'],
            ['name' => 'Physics 1', 'code' => 'PHY1', 'exam_date' => '2027-05-05', 'start_time' => '13:00'],
            ['name' => 'Calculus AB', 'code' => 'CALAB', 'exam_date' => '2027-05-10', 'start_time' => '09:00'],
            ['name' => 'Calculus BC', 'code' => 'CALBC', 'exam_date' => '2027-05-10', 'start_time' => '09:00'],
            ['name' => 'Statistics', 'code' => 'STAT', 'exam_date' => '2027-05-11', 'start_time' => '13:00'],
            ['name' => 'Computer Science A', 'code' => 'CSA', 'exam_date' => '2027-05-12', 'start_time' => '13:00'],
            ['name' => 'English Language and Composition', 'code' => 'ENGLANG', 'exam_date' => '2027-05-12', 'start_time' => '09:00'],
            ['name' => 'Macroeconomics', 'code' => 'MACRO', 'exam_date' => '2027-05-07', 'start_time' => '13:00'],
            ['name' => 'Psychology', 'code' => 'PSY', 'exam_date' => '2027-05-14', 'start_time' => '13:00'],
            ['name' => 'Chinese Language and Culture', 'code' => 'CHN', 'exam_date' => '2027-05-13', 'start_time' => '13:00'],
        ];

        foreach ($subjects as $index => $subject) {
            $model = ApExamSubject::withTrashed()->firstOrNew(['code' => $subject['code']]);
            $model->fill($subject + [
                'exam_season_id' => $season?->id,
                'category' => in_array($subject['code'], ['CALAB', 'CALBC', 'STAT'], true) ? 'Mathematics' : (in_array($subject['code'], ['BIO', 'CHEM', 'PHY1'], true) ? 'Sciences' : 'General'),
                'description' => 'Official AP exam registration subject.',
                'end_time' => null,
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
            ]);
            $model->uuid ??= (string) Str::uuid();
            $model->deleted_at = null;
            $model->save();
        }
    }
}
