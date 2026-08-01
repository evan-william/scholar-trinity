<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('landing_sections')->where('key', 'registration_intro')->exists()) {
            return;
        }

        DB::table('landing_sections')->insert([
            'key' => 'registration_intro',
            'eyebrow' => 'No login required',
            'title' => '2027 AP Exam Registration',
            'body' => 'Students can submit registration details, passport upload, exam selections, accommodations, and payment method in one guided flow.',
            'items' => json_encode([
                'Main registration is normally available from August through October.',
                'Late Registration Period may open from mid November through mid March if seats remain.',
                'Registration is finalized after the form, payment, and official confirmation email are received.',
            ]),
            'is_active' => true,
            'sort_order' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        // Preserve any content that an administrator may already have edited.
    }
};
