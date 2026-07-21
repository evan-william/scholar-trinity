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
            ['group' => 'seo', 'key' => 'meta_title', 'value' => ['text' => 'AP Exam Registration | TPCA x Trinity Scholar']],
            ['group' => 'seo', 'key' => 'meta_description', 'value' => ['text' => 'Register for AP exams as an outside student through a secure TPCA and Trinity Scholar registration process.']],
            ['group' => 'seo', 'key' => 'keywords', 'value' => ['text' => 'AP Exam, Taiwan AP registration, TPCA, Trinity Scholar']],
            ['group' => 'seo', 'key' => 'canonical_url', 'value' => ['text' => url('/')]],
            ['group' => 'hero', 'key' => 'platform_name', 'value' => ['text' => 'TPCA x Trinity Scholar']],
            ['group' => 'hero', 'key' => 'title', 'value' => ['text' => 'AP Exam Registration']],
            ['group' => 'hero', 'key' => 'introduction', 'value' => ['text' => 'A secure, guided registration platform for outside students and parents preparing for AP exams in Taiwan.']],
            ['group' => 'hero', 'key' => 'primary_button', 'value' => ['text' => 'Register Now']],
            ['group' => 'hero', 'key' => 'secondary_button', 'value' => ['text' => 'Learn More']],
            ['group' => 'hero', 'key' => 'banner_text', 'value' => ['text' => 'Registration, passport upload, fee review, and confirmation in one guided flow.']],
        ];

        foreach ($settings as $setting) {
            LandingSetting::query()->updateOrCreate(
                ['group' => $setting['group'], 'key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }

        $sections = [
            'overview' => [
                'eyebrow' => 'Program Overview',
                'title' => 'Everything families need before registering',
                'body' => 'Advanced Placement exams let students demonstrate college-level academic readiness. This registration platform helps outside students submit required personal information, select exams, upload passport documentation, review fees, and prepare for payment confirmation.',
                'items' => ['Outside students may register when seats are available.', 'TPCA coordinates exam availability and review.', 'Trinity Scholar supports the first registration intake stage.'],
                'sort_order' => 10,
            ],
            'process' => [
                'eyebrow' => 'Registration Process',
                'title' => 'A clear step-by-step workflow',
                'body' => 'Students and parents should read the requirements first, prepare documents, complete the registration form, review all information, then finish payment after confirmation instructions are provided.',
                'items' => ['Fill registration form', 'Upload documents', 'Review and submit', 'Payment', 'Confirmation'],
                'sort_order' => 20,
            ],
            'privacy' => [
                'eyebrow' => 'Privacy Notice',
                'title' => 'Personal data is handled with care',
                'body' => 'Registration data is used only for exam coordination, identity verification, payment review, receipt handling, and required parent/student communication. Passport uploads are stored privately and should be retained only as long as needed for exam administration and audit requirements.',
                'items' => ['Passport files are not stored in public web directories.', 'Personal information is used only for registration operations.', 'Consent is required before submission.', 'Privacy Policy and Terms links can be configured before launch.'],
                'sort_order' => 30,
            ],
        ];

        foreach ($sections as $key => $section) {
            LandingSection::query()->updateOrCreate(['key' => $key], $section + ['is_active' => true]);
        }

        $timeline = [
            ['round' => 'Main Registration', 'month' => 'August', 'status' => 'Upcoming', 'description' => 'Registration window preparation and document collection begins.'],
            ['round' => 'Main Registration', 'month' => 'September', 'status' => 'Open', 'description' => 'Primary registration intake and review period.'],
            ['round' => 'Main Registration', 'month' => 'October', 'status' => 'Closed', 'description' => 'Final review and payment confirmation for regular registration.'],
            ['round' => 'Late Registration', 'month' => 'January', 'status' => 'Upcoming', 'description' => 'Late registration opens if seats and subjects remain available.'],
            ['round' => 'Late Registration', 'month' => 'February', 'status' => 'Upcoming', 'description' => 'Late registration review and payment processing.'],
            ['round' => 'Late Registration', 'month' => 'March', 'status' => 'Closed', 'description' => 'Late registration closes before final exam administration preparation.'],
        ];

        LandingTimeline::query()->delete();
        foreach ($timeline as $index => $row) {
            LandingTimeline::query()->create($row + ['sort_order' => $index, 'is_active' => true]);
        }

        $fees = [
            ['name' => 'AP Exam Fee', 'description' => 'Collected for the official AP exam registration.', 'currency' => 'NTD', 'amount' => 7800],
            ['name' => 'Trinity Service Fee', 'description' => 'Service handling fee. Fapiao applies to this fee only.', 'currency' => 'NTD', 'amount' => 1200],
            ['name' => 'Late Registration Fee', 'description' => 'Applied during January to March late registration.', 'currency' => 'NTD', 'amount' => 1500],
        ];

        LandingFee::query()->delete();
        foreach ($fees as $index => $fee) {
            LandingFee::query()->create($fee + ['sort_order' => $index, 'is_active' => true]);
        }

        $documents = [
            ['name' => 'Student Information', 'description' => 'Legal name, school, grade, date of birth, and a personal student email address. School email addresses should not be used.', 'is_required' => true],
            ['name' => 'Valid Student Passport', 'description' => 'Clear passport photo page or PDF upload.', 'is_required' => true],
            ['name' => 'Parent Information', 'description' => 'Parent or guardian contact and mailing address.', 'is_required' => true],
            ['name' => 'Accommodation Documents', 'description' => 'Required only when requesting College Board approved accommodations.', 'is_required' => false],
        ];

        LandingRequiredDocument::query()->delete();
        foreach ($documents as $index => $document) {
            LandingRequiredDocument::query()->create($document + ['sort_order' => $index, 'is_active' => true]);
        }

        $faqs = [
            ['question' => 'What is AP?', 'answer' => 'AP stands for Advanced Placement, a program that allows students to take college-level courses and exams.'],
            ['question' => 'Who can register?', 'answer' => 'Any high school student, homeschooled student, or independent learner.'],
            ['question' => 'What is the age requirement?', 'answer' => 'There is no minimum age, but AP is designed for high school students in grades 9 through 12. College Board generally does not permit students over age 21 to take the exams.'],
            ['question' => 'Can I change my exam?', 'answer' => 'Changes depend on availability, deadlines, and coordinator approval. Contact the team as early as possible.'],
            ['question' => 'Can I upload passport later?', 'answer' => 'Passport upload is required during registration so identity details can be verified before payment confirmation.'],
            ['question' => 'When is payment due?', 'answer' => 'Payment instructions are confirmed after registration review. Families should complete payment before the deadline for their registration round.'],
            ['question' => 'How do I receive confirmation?', 'answer' => 'A confirmation message is sent to the student and parent email after registration details and payment are reviewed.'],
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
                'social_links' => ['Line: @TrinityScholar'],
            ]
        );
    }
}
