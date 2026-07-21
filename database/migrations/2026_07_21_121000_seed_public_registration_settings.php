<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('system_settings')) {
            $settings = [
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

            foreach ($settings as $key => $value) {
                if (! DB::table('system_settings')->where('key', $key)->exists()) {
                    DB::table('system_settings')->insert([
                        'uuid' => (string) Str::uuid(),
                        'group' => 'registration',
                        'key' => $key,
                        'value' => $value,
                        'type' => 'string',
                        'description' => 'Public registration content editable from the admin system settings page.',
                        'is_public' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        if (Schema::hasTable('email_template_settings')) {
            $templates = [
                'en' => ['AP registration received - {{ registration_number }}', '<p>Hello {{ student_name }},</p><p>We received your AP exam registration.</p><p><strong>Reference:</strong> {{ registration_number }}<br><strong>Submitted:</strong> {{ submitted_at }}<br><strong>Selected exams:</strong> {{ selected_exams }}</p><p>Open your registration page to receive bank-transfer instructions and upload payment proof. Registration is finalized only after payment review and an official confirmation email.</p>'],
                'zh_TW' => ['已收到 AP 考試報名 - {{ registration_number }}', '<p>{{ student_name }} 您好：</p><p>我們已收到您的 AP 考試報名。</p><p><strong>參考編號：</strong>{{ registration_number }}<br><strong>提交時間：</strong>{{ submitted_at }}<br><strong>所選考科：</strong>{{ selected_exams }}</p><p>請開啟報名頁面查看銀行轉帳說明並上傳付款證明。款項審核完成且收到官方確認信後，報名才算完成。</p>'],
            ];

            foreach ($templates as $locale => [$subject, $body]) {
                $exists = DB::table('email_template_settings')
                    ->where('template_key', 'student_registration_confirmation')
                    ->where('locale', $locale)
                    ->exists();

                if (! $exists) {
                    DB::table('email_template_settings')->insert([
                        'uuid' => (string) Str::uuid(),
                        'template_key' => 'student_registration_confirmation',
                        'locale' => $locale,
                        'subject' => $subject,
                        'body_html' => $body,
                        'body_text' => strip_tags($body),
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        // Preserve administrator-edited content during rollback.
    }
};
