<?php

namespace Tests\Feature;

use Database\Seeders\LandingPageSeeder;
use App\Models\LandingFaq;
use App\Models\LandingSection;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_displays_mvp_sections(): void
    {
        $this->seed(LandingPageSeeder::class);

        $this->get('/')
            ->assertOk()
            ->assertSee('AP Exam Registration')
            ->assertSee('Registration Timeline')
            ->assertSee('timeline-card-range', false)
            ->assertSee('Required Documents')
            ->assertSee('Frequently Asked Questions')
            ->assertSee('schema.org', false)
            ->assertSee(route('student-registrations.create'), false);
    }

    public function test_landing_page_escapes_cms_content(): void
    {
        $this->seed(LandingPageSeeder::class);

        \App\Models\LandingSection::query()->where('key', 'overview')->update([
            'title' => '<script>alert("xss")</script>',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('<script>alert("xss")</script>', false)
            ->assertSee('&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;', false);
    }

    public function test_admin_can_update_landing_content(): void
    {
        $this->seed(LandingPageSeeder::class);
        $this->actingAs($this->adminUser());

        $payload = $this->validAdminPayload();
        $payload['settings']['hero']['title'] = 'Updated AP Registration';
        $payload['sections']['registration_intro']['title'] = 'Updated Registration Intro';
        $payload['faqs'][0]['answer'] = 'Updated answer for families.';
        $payload['faqs'][] = ['question' => 'Can FAQs be added?', 'answer' => 'Yes, from Landing Content.'];
        $payload['registration_settings']['main_test_period'] = 'May 4-15, 2027';

        $this->put('/admin/landing', $payload)
            ->assertRedirect(route('admin.landing.edit'));

        $this->get('/')
            ->assertSee('Updated AP Registration')
            ->assertSee('Updated answer for families.')
            ->assertSee('Can FAQs be added?')
            ->assertSee('May 4-15, 2027');

        $this->get('/student-registration')
            ->assertOk()
            ->assertSee('Updated Registration Intro');

        $this->assertSame(2, LandingFaq::query()->count());
        $this->assertSame('May 4-15, 2027', SystemSetting::query()->where('key', 'registration.main_test_period')->value('value'));
    }

    public function test_landing_content_editor_exposes_schedule_test_site_and_dynamic_faq_controls(): void
    {
        $this->seed(LandingPageSeeder::class);

        $this->actingAs($this->adminUser())
            ->get(route('admin.landing.edit'))
            ->assertOk()
            ->assertSee('Registration Schedule and Test Site')
            ->assertSee('Add FAQ')
            ->assertSee('registration_settings[main_period][en]', false)
            ->assertSee('registration_settings[test_site_map_url][en]', false)
            ->assertSee('Registration Form Important Notice');
    }

    public function test_admin_can_publish_english_and_chinese_content_to_landing_and_form(): void
    {
        $this->seed(LandingPageSeeder::class);
        $this->actingAs($this->adminUser());
        $payload = $this->validAdminPayload();
        $payload['settings']['hero']['title'] = ['en' => 'English AP Registration', 'zh_TW' => '中文 AP 考試報名'];
        $payload['settings']['copy']['cta_title'] = ['en' => 'Start the English form', 'zh_TW' => '開始填寫中文報名表'];
        $payload['timelines'][0]['round'] = ['en' => 'Main Registration', 'zh_TW' => '中文一般報名'];
        $payload['timelines'][0]['month'] = ['en' => 'August', 'zh_TW' => '八月'];
        $payload['timelines'][0]['description'] = ['en' => 'English timeline copy.', 'zh_TW' => '中文時程內容。'];
        $payload['contact']['address'] = ['en' => 'Taipei office', 'zh_TW' => '台北中文地址'];
        $payload['sections']['registration_notice'] = [
            'eyebrow' => ['en' => 'Please note', 'zh_TW' => '請注意'],
            'title' => ['en' => 'Important Notice', 'zh_TW' => '報名重要提醒'],
            'body' => ['en' => 'English notice body.', 'zh_TW' => '這是中文提醒內容。'],
            'items' => ['en' => "English item one\nEnglish item two", 'zh_TW' => "中文事項一\n中文事項二"],
            'sort_order' => 6,
        ];

        $this->put(route('admin.landing.update'), $payload)
            ->assertRedirect(route('admin.landing.edit'))
            ->assertSessionHasNoErrors();

        $notice = LandingSection::query()->where('key', 'registration_notice')->firstOrFail();
        $this->assertSame('報名重要提醒', data_get($notice->translations, 'zh_TW.title'));

        $this->withSession(['locale' => 'zh-TW'])
            ->get(route('landing'))
            ->assertOk()
            ->assertSee('中文 AP 考試報名')
            ->assertSee('開始填寫中文報名表')
            ->assertSee('中文一般報名')
            ->assertSee('中文時程內容。')
            ->assertSee('台北中文地址');

        $this->withSession(['locale' => 'zh-TW'])
            ->get(route('student-registrations.create'))
            ->assertOk()
            ->assertSee('報名重要提醒')
            ->assertSee('這是中文提醒內容。')
            ->assertSee('中文事項一');
    }

    private function adminUser(): User
    {
        return User::query()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('StrongPass!123'),
            'is_admin' => true,
        ]);
    }

    public function test_traditional_chinese_language_file_is_available(): void
    {
        app()->setLocale('zh_TW');

        $this->assertSame('立即報名', __('landing.register_now'));
    }

    private function validAdminPayload(): array
    {
        return [
            'settings' => [
                'seo' => [
                    'meta_title' => 'AP Exam Registration | Test',
                    'meta_description' => 'Secure AP exam registration for students and parents.',
                    'keywords' => 'AP, Taiwan',
                    'canonical_url' => 'http://localhost',
                ],
                'hero' => [
                    'platform_name' => 'TPCA x Trinity Scholar',
                    'title' => 'AP Exam Registration',
                    'introduction' => 'A secure test introduction.',
                    'primary_button' => 'Register Now',
                    'secondary_button' => 'Learn More',
                    'banner_text' => 'Guided registration flow.',
                ],
            ],
            'sections' => [
                'registration_intro' => ['eyebrow' => 'No login required', 'title' => 'Registration Intro', 'body' => 'Registration intro body', 'items' => "One\nTwo", 'sort_order' => 5],
                'registration_notice' => ['eyebrow' => 'Please note', 'title' => 'Important Notice', 'body' => 'Important notice body', 'items' => "One\nTwo", 'sort_order' => 6],
                'overview' => ['eyebrow' => 'Overview', 'title' => 'Overview Title', 'body' => 'Overview body', 'items' => "One\nTwo", 'sort_order' => 10],
                'process' => ['eyebrow' => 'Process', 'title' => 'Process Title', 'body' => 'Process body', 'items' => "Read\nPay", 'sort_order' => 20],
                'privacy' => ['eyebrow' => 'Privacy', 'title' => 'Privacy Title', 'body' => 'Privacy body', 'items' => "Consent\nRetention", 'sort_order' => 30],
            ],
            'registration_settings' => [
                'main_period' => 'August - October',
                'late_period' => 'Mid November - Mid March',
                'main_test_period' => 'May 3-14, 2027',
                'late_test_period' => 'May 17 - 21, 2027',
                'test_site_name_en' => 'The Primacy Collegiate Academy',
                'test_site_name_zh' => '基督教美國高中課程',
                'test_site_address_en' => 'No. 99, Meide St, Shilin District, Taipei City, 11159',
                'test_site_address_zh' => '台北市士林區美德街99號',
                'test_site_map_url' => 'https://www.google.com/maps/search/?api=1&query=Taipei',
            ],
            'timelines' => [
                ['round' => 'Main Registration', 'month' => 'August', 'status' => 'Open', 'description' => 'Open now'],
            ],
            'fees' => [
                ['name' => 'AP Exam Fee', 'description' => 'Exam fee', 'currency' => 'NTD', 'amount' => 7800],
                ['name' => 'Service Fee', 'description' => 'Service fee', 'currency' => 'NTD', 'amount' => 1200],
            ],
            'documents' => [
                ['name' => 'Passport', 'description' => 'Passport page', 'is_required' => '1'],
            ],
            'faqs' => [
                ['question' => 'What is AP?', 'answer' => 'AP is Advanced Placement.'],
            ],
            'contact' => [
                'organization' => 'Trinity Scholar',
                'email' => 'hello@example.com',
                'phone' => '+886 2 0000 0000',
                'whatsapp' => '+886 900 000 000',
                'office_hours' => 'Mon-Fri',
                'address' => 'Taipei',
                'map_url' => 'http://localhost/map',
                'social_links' => "Website: http://localhost",
            ],
        ];
    }
}
