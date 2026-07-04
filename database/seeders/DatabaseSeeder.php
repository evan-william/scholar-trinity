<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(LandingPageSeeder::class);
        $this->call(ExamSeasonSeeder::class);
        $this->call(ApExamSubjectSeeder::class);
        $this->call(PaymentSettingSeeder::class);

        if (app()->environment(['local', 'testing'])) {
            User::query()->updateOrCreate(
                ['email' => 'test@example.com'],
                [
                    'name' => 'Test User',
                    'password' => Hash::make('StrongPass!123'),
                    'is_admin' => true,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
