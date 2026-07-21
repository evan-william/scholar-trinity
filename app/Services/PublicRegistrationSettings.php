<?php

namespace App\Services;

use App\Models\SystemSetting;
use Throwable;

class PublicRegistrationSettings
{
    private const DEFAULTS = [
        'registration.main_period' => 'August - October',
        'registration.late_period' => 'January - March',
        'registration.main_test_period' => 'Beginning of May',
        'registration.late_test_period' => 'Mid to late May',
        'registration.test_site_name_en' => 'The Primacy Collegiate Academy',
        'registration.test_site_name_zh' => '基督教美國高中課程',
        'registration.test_site_address_en' => 'No. 99, Meide St, Shilin District, Taipei City, 11159',
        'registration.test_site_address_zh' => '台北市士林區美德街99號',
        'registration.test_site_map_url' => 'https://www.google.com/maps/search/?api=1&query=No.+99%2C+Meide+St%2C+Shilin+District%2C+Taipei+City+11159',
    ];

    public function all(): array
    {
        try {
            $stored = SystemSetting::query()
                ->whereIn('key', array_keys(self::DEFAULTS))
                ->pluck('value', 'key')
                ->all();
        } catch (Throwable) {
            $stored = [];
        }

        return collect(self::DEFAULTS)
            ->mapWithKeys(fn (string $default, string $key) => [
                str_replace('registration.', '', $key) => filled($stored[$key] ?? null) ? $stored[$key] : $default,
            ])
            ->all();
    }
}
