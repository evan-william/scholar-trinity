<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const TABLES = [
        'landing_sections',
        'landing_timelines',
        'landing_fees',
        'landing_required_documents',
        'landing_faqs',
        'landing_contacts',
    ];

    public function up(): void
    {
        foreach (self::TABLES as $tableName) {
            if (Schema::hasTable($tableName) && ! Schema::hasColumn($tableName, 'translations')) {
                Schema::table($tableName, function (Blueprint $table): void {
                    $table->json('translations')->nullable();
                });
            }
        }

        if (Schema::hasTable('landing_sections') && ! DB::table('landing_sections')->where('key', 'registration_notice')->exists()) {
            DB::table('landing_sections')->insert([
                    'key' => 'registration_notice',
                    'eyebrow' => 'Please note',
                    'title' => 'Important Notice',
                    'body' => 'Except AP Chinese, late or exception exam sessions are not offered.',
                    'items' => json_encode(['Once payment is submitted, cancelled exams are non-refundable.']),
                    'translations' => json_encode([
                        'en' => [
                            'eyebrow' => 'Please note',
                            'title' => 'Important Notice',
                            'body' => 'Except AP Chinese, late or exception exam sessions are not offered.',
                            'items' => ['Once payment is submitted, cancelled exams are non-refundable.'],
                        ],
                        'zh_TW' => [
                            'eyebrow' => '請注意',
                            'title' => '重要提醒',
                            'body' => '除 AP 中文外，不提供逾期或特殊考試場次。',
                            'items' => ['付款完成後，取消考試恕不退款。'],
                        ],
                    ], JSON_UNESCAPED_UNICODE),
                    'is_active' => true,
                    'sort_order' => 6,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
        }

        $this->backfillLandingSettings();
        $this->backfillContentTranslations();
    }

    public function down(): void
    {
        foreach (self::TABLES as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'translations')) {
                Schema::table($tableName, function (Blueprint $table): void {
                    $table->dropColumn('translations');
                });
            }
        }
    }

    private function backfillLandingSettings(): void
    {
        if (! Schema::hasTable('landing_settings')) {
            return;
        }

        $chinese = [
            'seo.meta_title' => 'AP 考試報名 | TPCA x Trinity Scholar',
            'seo.meta_description' => '校外學生可透過 TPCA 與 Trinity Scholar 的安全流程報名 AP 考試。',
            'seo.keywords' => 'AP 考試, 台灣 AP 報名, TPCA, Trinity Scholar',
            'hero.platform_name' => 'TPCA x Trinity Scholar',
            'hero.title' => 'AP 考試報名',
            'hero.introduction' => '為準備在台灣參加 AP 考試的校外學生與家長提供安全、清楚的報名流程。',
            'hero.primary_button' => '立即報名',
            'hero.secondary_button' => '了解更多',
            'hero.banner_text' => '透過單一引導流程完成報名、護照上傳、費用確認與最終確認。',
        ];

        foreach (DB::table('landing_settings')->get() as $setting) {
            $value = json_decode((string) $setting->value, true) ?: [];
            $english = (string) ($value['en'] ?? $value['text'] ?? '');
            $lookup = $setting->group.'.'.$setting->key;
            $value['en'] = $english;
            $value['zh_TW'] = $value['zh_TW'] ?? $chinese[$lookup] ?? $english;
            $value['text'] = $english;

            DB::table('landing_settings')->where('id', $setting->id)->update([
                'value' => json_encode($value, JSON_UNESCAPED_UNICODE),
                'updated_at' => now(),
            ]);
        }

        foreach (config('landing_copy', []) as $group) {
            foreach ($group['fields'] as $key => $copy) {
                DB::table('landing_settings')->updateOrInsert(
                    ['group' => 'copy', 'key' => $key],
                    [
                        'value' => json_encode([
                            'text' => $copy['en'],
                            'en' => $copy['en'],
                            'zh_TW' => $copy['zh_TW'],
                        ], JSON_UNESCAPED_UNICODE),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }

    private function backfillContentTranslations(): void
    {
        $sectionZh = [
            'registration_intro' => ['eyebrow' => '無需登入', 'title' => '2027 AP 考試報名', 'body' => '學生可透過單一引導流程提交報名資料、護照、考試選擇、特殊應試需求與付款方式。', 'items' => ['一般報名期間：八月至十月。', '若仍有名額，逾期報名期間為十一月中旬至三月中旬。', '收到表單、付款及官方確認信後，報名才算完成。']],
            'registration_notice' => ['eyebrow' => '請注意', 'title' => '重要提醒', 'body' => '除 AP 中文外，不提供逾期或特殊考試場次。', 'items' => ['付款完成後，取消考試恕不退款。']],
            'overview' => ['eyebrow' => '課程概覽', 'title' => '報名前家庭需要了解的資訊', 'body' => 'AP 考試讓學生展現大學程度的學術能力。本平台協助校外學生提交個人資料、選擇考試、上傳護照、確認費用並準備付款。', 'items' => ['有剩餘名額時，校外學生可報名。', 'TPCA 負責考試名額與資料審核。', 'Trinity Scholar 協助初步報名流程。']],
            'process' => ['eyebrow' => '報名流程', 'title' => '清楚的逐步流程', 'body' => '學生與家長應先閱讀要求、準備文件、填寫報名表並確認所有資料，再依確認指示完成付款。', 'items' => ['填寫報名表', '上傳文件', '檢查並提交', '付款', '確認完成']],
            'privacy' => ['eyebrow' => '隱私權提醒', 'title' => '妥善保護個人資料', 'body' => '報名資料僅用於考試協調、身分驗證、付款審核、收據處理及必要聯絡。護照文件將以私密方式保存，並依管理與稽核需求保留。', 'items' => ['護照文件不會存放在公開網站目錄。', '個人資料僅用於報名作業。', '提交前必須取得同意。', '上線前可設定隱私權政策與條款連結。']],
        ];
        $this->backfillTable('landing_sections', ['eyebrow', 'title', 'body', 'items'], fn ($row) => $sectionZh[$row->key] ?? []);

        $timelineZh = [
            ['round' => '一般報名', 'month' => '八月', 'description' => '開始準備報名時程並收集文件。'],
            ['round' => '一般報名', 'month' => '九月', 'description' => '主要報名受理與審核期間。'],
            ['round' => '一般報名', 'month' => '十月', 'description' => '一般報名的最終審核與付款確認。'],
            ['round' => '逾期報名', 'month' => '十一月中旬', 'description' => '若仍有考試名額與科目，將開放逾期報名。'],
            ['round' => '逾期報名', 'month' => '二月', 'description' => '進行逾期報名審核與付款處理。'],
            ['round' => '逾期報名', 'month' => '三月中旬', 'description' => '在最終考務準備前結束逾期報名。'],
        ];
        $this->backfillTable('landing_timelines', ['round', 'month', 'description'], fn ($row) => $timelineZh[(int) $row->sort_order] ?? []);

        $feeZh = [
            ['name' => 'AP 考試費', 'description' => '用於正式 AP 考試報名。'],
            ['name' => 'Trinity 服務費', 'description' => '報名服務處理費；發票僅適用於此費用。'],
            ['name' => '逾期報名費', 'description' => '適用於十一月中旬至三月中旬的逾期報名期間。'],
        ];
        $this->backfillTable('landing_fees', ['name', 'description'], fn ($row) => $feeZh[(int) $row->sort_order] ?? []);

        $documentZh = [
            ['name' => '學生資料', 'description' => '法定姓名、學校、年級、出生日期及學生個人電子郵件；請勿使用學校信箱。'],
            ['name' => '有效學生護照', 'description' => '上傳清楚的護照照片頁或 PDF。'],
            ['name' => '家長資料', 'description' => '家長或監護人的聯絡方式與郵寄地址。'],
            ['name' => '特殊應試文件', 'description' => '僅在申請 College Board 核准之特殊應試安排時需要。'],
        ];
        $this->backfillTable('landing_required_documents', ['name', 'description'], fn ($row) => $documentZh[(int) $row->sort_order] ?? []);

        $faqZh = [
            ['question' => '什麼是 AP？', 'answer' => 'AP 是 Advanced Placement（大學先修課程），讓學生修習大學程度課程並參加考試。'],
            ['question' => '誰可以報名？', 'answer' => '高中生、自學生或獨立學習者皆可報名。'],
            ['question' => '年齡限制為何？', 'answer' => 'AP 沒有最低年齡限制，但主要為 9 至 12 年級高中生設計。College Board 通常不允許 21 歲以上學生參加考試。'],
            ['question' => '可以更改考試科目嗎？', 'answer' => '是否能更改取決於名額、截止日期與協調員核准，請儘早聯絡團隊。'],
            ['question' => '可以稍後再上傳護照嗎？', 'answer' => '報名時必須上傳護照，以便在付款確認前核對身分資料。'],
            ['question' => '付款截止時間為何？', 'answer' => '審核報名資料後會提供付款指示，請於所屬報名梯次截止日前完成付款。'],
            ['question' => '如何收到確認？', 'answer' => '報名資料與付款審核完成後，確認訊息會寄到學生與家長的電子郵件。'],
        ];
        $this->backfillTable('landing_faqs', ['question', 'answer'], fn ($row) => $faqZh[(int) $row->sort_order] ?? []);

        $this->backfillTable('landing_contacts', ['organization', 'office_hours', 'address', 'social_links'], fn () => [
            'organization' => 'Trinity Scholar',
            'office_hours' => '週一至週五 09:00-18:00',
            'address' => '台北市士林區美德街 99 號',
            'social_links' => ['Line: https://lin.ee/VXnDLUW'],
        ]);
    }

    private function backfillTable(string $table, array $fields, callable $chineseValues): void
    {
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'translations')) {
            return;
        }

        $query = DB::table($table);
        if (Schema::hasColumn($table, 'sort_order')) {
            $query->orderBy('sort_order');
        }

        foreach ($query->get() as $row) {
            $translations = json_decode((string) ($row->translations ?? ''), true) ?: [];
            $english = [];

            foreach ($fields as $field) {
                $value = $row->{$field} ?? null;
                $english[$field] = $field === 'items' || $field === 'social_links'
                    ? (json_decode((string) $value, true) ?: [])
                    : $value;
            }

            $translations['en'] = $translations['en'] ?? $english;
            $translations['zh_TW'] = array_merge($english, $translations['zh_TW'] ?? [], $chineseValues($row));

            DB::table($table)->where('id', $row->id)->update([
                'translations' => json_encode($translations, JSON_UNESCAPED_UNICODE),
                'updated_at' => now(),
            ]);
        }
    }
};
