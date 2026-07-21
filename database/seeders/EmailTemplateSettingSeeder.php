<?php

namespace Database\Seeders;

use App\Models\EmailTemplateSetting;
use Illuminate\Database\Seeder;

class EmailTemplateSettingSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            'en' => [
                'subject' => 'AP registration received - {{ registration_number }}',
                'body_html' => '<p>Hello {{ student_name }},</p><p>We received your AP exam registration.</p><p><strong>Reference:</strong> {{ registration_number }}<br><strong>Submitted:</strong> {{ submitted_at }}<br><strong>Selected exams:</strong> {{ selected_exams }}</p><p>Open your registration page to receive bank-transfer instructions and upload payment proof. Registration is finalized only after payment review and an official confirmation email.</p>',
            ],
            'zh_TW' => [
                'subject' => '已收到 AP 考試報名 - {{ registration_number }}',
                'body_html' => '<p>{{ student_name }} 您好：</p><p>我們已收到您的 AP 考試報名。</p><p><strong>參考編號：</strong>{{ registration_number }}<br><strong>提交時間：</strong>{{ submitted_at }}<br><strong>所選考科：</strong>{{ selected_exams }}</p><p>請開啟報名頁面查看銀行轉帳說明並上傳付款證明。款項審核完成且收到官方確認信後，報名才算完成。</p>',
            ],
        ];

        foreach ($templates as $locale => $template) {
            EmailTemplateSetting::query()->firstOrCreate(
                ['template_key' => 'student_registration_confirmation', 'locale' => $locale],
                $template + ['body_text' => strip_tags($template['body_html']), 'is_active' => true]
            );
        }
    }
}
