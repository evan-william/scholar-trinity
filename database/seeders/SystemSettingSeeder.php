<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'registration.main_period', 'value' => 'August - October', 'description' => 'Public main registration period.'],
            ['key' => 'registration.late_period', 'value' => 'January - March', 'description' => 'Public late registration period.'],
            ['key' => 'registration.main_test_period', 'value' => 'Beginning of May', 'description' => 'Usual AP main test period shown publicly.'],
            ['key' => 'registration.late_test_period', 'value' => 'Mid to late May', 'description' => 'Usual AP late test period shown publicly.'],
            ['key' => 'registration.test_site_name_en', 'value' => 'The Primacy Collegiate Academy', 'description' => 'English public test-site name.'],
            ['key' => 'registration.test_site_name_zh', 'value' => '基督教美國高中課程', 'description' => 'Traditional Chinese public test-site name.'],
            ['key' => 'registration.test_site_address_en', 'value' => 'No. 99, Meide St, Shilin District, Taipei City, 11159', 'description' => 'English public test-site address.'],
            ['key' => 'registration.test_site_address_zh', 'value' => '台北市士林區美德街99號', 'description' => 'Traditional Chinese public test-site address.'],
            ['key' => 'registration.test_site_map_url', 'value' => 'https://www.google.com/maps/search/?api=1&query=No.+99%2C+Meide+St%2C+Shilin+District%2C+Taipei+City+11159', 'description' => 'Public test-site map link.'],
        ];

        foreach ($settings as $setting) {
            SystemSetting::query()->firstOrCreate(
                ['key' => $setting['key']],
                $setting + ['group' => 'registration', 'type' => 'string', 'is_public' => true]
            );
        }
    }
}
