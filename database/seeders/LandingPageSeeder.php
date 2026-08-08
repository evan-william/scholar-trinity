<?php

namespace Database\Seeders;

use App\Models\LandingContact;
use App\Models\LandingFaq;
use App\Models\LandingFee;
use App\Models\LandingRequiredDocument;
use App\Models\LandingSection;
use App\Models\LandingSetting;
use App\Models\LandingTimeline;
use Illuminate\Database\Seeder;

class LandingPageSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['group' => 'seo', 'key' => 'meta_title', 'value' => $this->translated('AP Exam Registration | TPCA x Trinity Scholar', 'AP 考試報名 | TPCA x Trinity Scholar')],
            ['group' => 'seo', 'key' => 'meta_description', 'value' => $this->translated('Register for AP exams as an outside student through a secure TPCA and Trinity Scholar registration process.', '校外學生可透過 TPCA 與 Trinity Scholar 的安全流程報名 AP 考試。')],
            ['group' => 'seo', 'key' => 'keywords', 'value' => $this->translated('AP Exam, Taiwan AP registration, TPCA, Trinity Scholar', 'AP 考試, 台灣 AP 報名, TPCA, Trinity Scholar')],
            ['group' => 'seo', 'key' => 'canonical_url', 'value' => $this->translated(url('/'), url('/'))],
            ['group' => 'hero', 'key' => 'platform_name', 'value' => $this->translated('TPCA x Trinity Scholar', 'TPCA x Trinity Scholar')],
            ['group' => 'hero', 'key' => 'title', 'value' => $this->translated('AP Exam Registration', 'AP 考試報名')],
            ['group' => 'hero', 'key' => 'introduction', 'value' => $this->translated('A secure, guided registration platform for outside students and parents preparing for AP exams in Taiwan.', '為準備在台灣參加 AP 考試的校外學生與家長提供安全、清楚的報名流程。')],
            ['group' => 'hero', 'key' => 'primary_button', 'value' => $this->translated('Register Now', '立即報名')],
            ['group' => 'hero', 'key' => 'secondary_button', 'value' => $this->translated('Learn More', '了解更多')],
            ['group' => 'hero', 'key' => 'banner_text', 'value' => $this->translated('Registration, passport upload, fee review, and confirmation in one guided flow.', '透過單一引導流程完成報名、護照上傳、費用確認與最終確認。')],
        ];

        foreach ($settings as $setting) {
            LandingSetting::query()->updateOrCreate(
                ['group' => $setting['group'], 'key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }

        foreach (config('landing_copy', []) as $group) {
            foreach ($group['fields'] as $key => $copy) {
                LandingSetting::query()->updateOrCreate(
                    ['group' => 'copy', 'key' => $key],
                    ['value' => $this->translated($copy['en'], $copy['zh_TW'])]
                );
            }
        }

        $sections = [
            'registration_intro' => [
                'eyebrow' => 'No login required',
                'title' => '2027 AP Exam Registration',
                'body' => 'Students can submit registration details, passport upload, exam selections, accommodations, and payment method in one guided flow.',
                'items' => ['Main Registration Period: August - October.', 'Late Registration Period: Mid November - Mid March.', 'Registration is finalized after the form, payment, and official confirmation email are received.'],
                'sort_order' => 5,
                'translations' => $this->sectionTranslation('無需登入', '2027 AP 考試報名', '學生可透過單一引導流程提交報名資料、護照、考試選擇、特殊應試需求與付款方式。', ['一般報名期間：八月至十月。', '若仍有名額，逾期報名期間為十一月中旬至三月中旬。', '收到表單、付款及官方確認信後，報名才算完成。']),
            ],
            'registration_notice' => [
                'eyebrow' => 'Please note',
                'title' => 'Important Notice',
                'body' => 'Except AP Chinese, late or exception exam sessions are not offered.',
                'items' => ['Once payment is submitted, cancelled exams are non-refundable.'],
                'sort_order' => 6,
                'translations' => $this->sectionTranslation('請注意', '重要提醒', '除 AP 中文外，不提供逾期或特殊考試場次。', ['付款完成後，取消考試恕不退款。']),
            ],
            'overview' => [
                'eyebrow' => 'Program Overview',
                'title' => 'Everything families need before registering',
                'body' => 'Advanced Placement exams let students demonstrate college-level academic readiness. This registration platform helps outside students submit required personal information, select exams, upload passport documentation, review fees, and prepare for payment confirmation.',
                'items' => ['Outside students may register when seats are available.', 'TPCA coordinates exam availability and review.', 'Trinity Scholar supports the first registration intake stage.'],
                'sort_order' => 10,
                'translations' => $this->sectionTranslation('課程概覽', '報名前家庭需要了解的資訊', 'AP 考試讓學生展現大學程度的學術能力。本平台協助校外學生提交個人資料、選擇考試、上傳護照、確認費用並準備付款。', ['有剩餘名額時，校外學生可報名。', 'TPCA 負責考試名額與資料審核。', 'Trinity Scholar 協助初步報名流程。']),
            ],
            'process' => [
                'eyebrow' => 'Registration Process',
                'title' => 'A clear step-by-step workflow',
                'body' => 'Students and parents should read the requirements first, prepare documents, complete the registration form, review all information, then finish payment after confirmation instructions are provided.',
                'items' => ['Fill registration form', 'Upload documents', 'Review and submit', 'Payment', 'Confirmation'],
                'sort_order' => 20,
                'translations' => $this->sectionTranslation('報名流程', '清楚的逐步流程', '學生與家長應先閱讀要求、準備文件、填寫報名表並確認所有資料，再依確認指示完成付款。', ['填寫報名表', '上傳文件', '檢查並提交', '付款', '確認完成']),
            ],
            'privacy' => [
                'eyebrow' => 'Privacy Notice',
                'title' => 'Personal data is handled with care',
                'body' => 'Registration data is used only for exam coordination, identity verification, payment review, receipt handling, and required parent/student communication. Passport uploads are stored privately and should be retained only as long as needed for exam administration and audit requirements.',
                'items' => ['Passport files are not stored in public web directories.', 'Personal information is used only for registration operations.', 'Consent is required before submission.', 'Privacy Policy and Terms links can be configured before launch.'],
                'sort_order' => 30,
                'translations' => $this->sectionTranslation('隱私權提醒', '妥善保護個人資料', '報名資料僅用於考試協調、身分驗證、付款審核、收據處理及必要聯絡。護照文件將以私密方式保存，並依管理與稽核需求保留。', ['護照文件不會存放在公開網站目錄。', '個人資料僅用於報名作業。', '提交前必須取得同意。', '上線前可設定隱私權政策與條款連結。']),
            ],
        ];

        foreach ($sections as $key => $section) {
            LandingSection::query()->updateOrCreate(['key' => $key], $section + ['is_active' => true]);
        }

        $timeline = [
            ['round' => 'Main Registration', 'month' => 'August', 'status' => 'Upcoming', 'description' => 'Registration window preparation and document collection begins.', 'translations' => $this->rowTranslation(['round' => '一般報名', 'month' => '八月', 'description' => '開始準備報名時程並收集文件。'])],
            ['round' => 'Main Registration', 'month' => 'September', 'status' => 'Open', 'description' => 'Primary registration intake and review period.', 'translations' => $this->rowTranslation(['round' => '一般報名', 'month' => '九月', 'description' => '主要報名受理與審核期間。'])],
            ['round' => 'Main Registration', 'month' => 'October', 'status' => 'Closed', 'description' => 'Final review and payment confirmation for regular registration.', 'translations' => $this->rowTranslation(['round' => '一般報名', 'month' => '十月', 'description' => '一般報名的最終審核與付款確認。'])],
            ['round' => 'Late Registration', 'month' => 'Mid November', 'status' => 'Upcoming', 'description' => 'Late registration opens if seats and subjects remain available.', 'translations' => $this->rowTranslation(['round' => '逾期報名', 'month' => '十一月中旬', 'description' => '若仍有考試名額與科目，將開放逾期報名。'])],
            ['round' => 'Late Registration', 'month' => 'February', 'status' => 'Upcoming', 'description' => 'Late registration review and payment processing.', 'translations' => $this->rowTranslation(['round' => '逾期報名', 'month' => '二月', 'description' => '進行逾期報名審核與付款處理。'])],
            ['round' => 'Late Registration', 'month' => 'Mid March', 'status' => 'Closed', 'description' => 'Late registration closes before final exam administration preparation.', 'translations' => $this->rowTranslation(['round' => '逾期報名', 'month' => '三月中旬', 'description' => '在最終考務準備前結束逾期報名。'])],
        ];

        LandingTimeline::query()->delete();
        foreach ($timeline as $index => $row) {
            LandingTimeline::query()->create($row + ['sort_order' => $index, 'is_active' => true]);
        }

        $fees = [
            ['name' => 'AP Exam Fee', 'description' => 'Collected for the official AP exam registration.', 'currency' => 'NTD', 'amount' => 7800, 'translations' => $this->rowTranslation(['name' => 'AP 考試費', 'description' => '用於正式 AP 考試報名。'])],
            ['name' => 'Trinity Service Fee', 'description' => 'Service handling fee. Fapiao applies to this fee only.', 'currency' => 'NTD', 'amount' => 1200, 'translations' => $this->rowTranslation(['name' => 'Trinity 服務費', 'description' => '報名服務處理費；發票僅適用於此費用。'])],
            ['name' => 'Late Registration Fee', 'description' => 'Applied during the mid-November to mid-March late registration period.', 'currency' => 'NTD', 'amount' => 1500, 'translations' => $this->rowTranslation(['name' => '逾期報名費', 'description' => '適用於十一月中旬至三月中旬的逾期報名期間。'])],
        ];

        LandingFee::query()->delete();
        foreach ($fees as $index => $fee) {
            LandingFee::query()->create($fee + ['sort_order' => $index, 'is_active' => true]);
        }

        $documents = [
            ['name' => 'Student Information', 'description' => 'Legal name, school, grade, date of birth, and a personal student email address. School email addresses should not be used.', 'is_required' => true, 'translations' => $this->rowTranslation(['name' => '學生資料', 'description' => '法定姓名、學校、年級、出生日期及學生個人電子郵件；請勿使用學校信箱。'])],
            ['name' => 'Valid Student Passport', 'description' => 'Clear passport photo page or PDF upload.', 'is_required' => true, 'translations' => $this->rowTranslation(['name' => '有效學生護照', 'description' => '上傳清楚的護照照片頁或 PDF。'])],
            ['name' => 'Parent Information', 'description' => 'Parent or guardian contact and mailing address.', 'is_required' => true, 'translations' => $this->rowTranslation(['name' => '家長資料', 'description' => '家長或監護人的聯絡方式與郵寄地址。'])],
            ['name' => 'Accommodation Documents', 'description' => 'Required only when requesting College Board approved accommodations.', 'is_required' => false, 'translations' => $this->rowTranslation(['name' => '特殊應試文件', 'description' => '僅在申請 College Board 核准之特殊應試安排時需要。'])],
        ];

        LandingRequiredDocument::query()->delete();
        foreach ($documents as $index => $document) {
            LandingRequiredDocument::query()->create($document + ['sort_order' => $index, 'is_active' => true]);
        }

        $faqs = [
            ['question' => 'What is AP?', 'answer' => 'AP stands for Advanced Placement, a program that allows students to take college-level courses and exams.', 'translations' => $this->rowTranslation(['question' => '什麼是 AP？', 'answer' => 'AP 是 Advanced Placement（大學先修課程），讓學生修習大學程度課程並參加考試。'])],
            ['question' => 'Who can register?', 'answer' => 'Any high school student, homeschooled student, or independent learner.', 'translations' => $this->rowTranslation(['question' => '誰可以報名？', 'answer' => '高中生、自學生或獨立學習者皆可報名。'])],
            ['question' => 'What is the age requirement?', 'answer' => 'There is no minimum age, but AP is designed for high school students in grades 9 through 12. College Board generally does not permit students over age 21 to take the exams.', 'translations' => $this->rowTranslation(['question' => '年齡限制為何？', 'answer' => 'AP 沒有最低年齡限制，但主要為 9 至 12 年級高中生設計。College Board 通常不允許 21 歲以上學生參加考試。'])],
            ['question' => 'Can I change my exam?', 'answer' => 'Changes depend on availability, deadlines, and coordinator approval. Contact the team as early as possible.', 'translations' => $this->rowTranslation(['question' => '可以更改考試科目嗎？', 'answer' => '是否能更改取決於名額、截止日期與協調員核准，請儘早聯絡團隊。'])],
            ['question' => 'Can I upload passport later?', 'answer' => 'Passport upload is required during registration so identity details can be verified before payment confirmation.', 'translations' => $this->rowTranslation(['question' => '可以稍後再上傳護照嗎？', 'answer' => '報名時必須上傳護照，以便在付款確認前核對身分資料。'])],
            ['question' => 'When is payment due?', 'answer' => 'Payment instructions are confirmed after registration review. Families should complete payment before the deadline for their registration round.', 'translations' => $this->rowTranslation(['question' => '付款截止時間為何？', 'answer' => '審核報名資料後會提供付款指示，請於所屬報名梯次截止日前完成付款。'])],
            ['question' => 'How do I receive confirmation?', 'answer' => 'A confirmation message is sent to the student and parent email after registration details and payment are reviewed.', 'translations' => $this->rowTranslation(['question' => '如何收到確認？', 'answer' => '報名資料與付款審核完成後，確認訊息會寄到學生與家長的電子郵件。'])],
        ];

        LandingFaq::query()->delete();
        foreach ($faqs as $index => $faq) {
            LandingFaq::query()->create($faq + ['sort_order' => $index, 'is_active' => true]);
        }

        LandingContact::query()->updateOrCreate(
            ['id' => LandingContact::query()->value('id')],
            [
                'organization' => 'Trinity Scholar',
                'email' => 'ap-registration@trinityscholar.com',
                'phone' => '886-2-2771-6002',
                'whatsapp' => '@TrinityScholar',
                'office_hours' => 'Monday to Friday, 09:00-18:00',
                'address' => 'No. 99, Meide St, Shilin District, Taipei City, 11159',
                'map_url' => 'https://www.google.com/maps/search/?api=1&query=No.+99%2C+Meide+St%2C+Shilin+District%2C+Taipei+City+11159',
                'social_links' => ['Line: https://lin.ee/VXnDLUW'],
                'translations' => $this->rowTranslation([
                    'organization' => 'Trinity Scholar',
                    'office_hours' => '週一至週五 09:00-18:00',
                    'address' => '台北市士林區美德街 99 號',
                    'social_links' => ['Line: https://lin.ee/VXnDLUW'],
                ]),
            ]
        );
    }

    private function translated(string $english, string $chinese): array
    {
        return ['text' => $english, 'en' => $english, 'zh_TW' => $chinese];
    }

    private function sectionTranslation(string $eyebrow, string $title, string $body, array $items): array
    {
        return ['zh_TW' => compact('eyebrow', 'title', 'body', 'items')];
    }

    private function rowTranslation(array $fields): array
    {
        return ['zh_TW' => $fields];
    }
}
