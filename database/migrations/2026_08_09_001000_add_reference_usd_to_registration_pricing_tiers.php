<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('registration_pricing_tiers')) {
            return;
        }

        if (! Schema::hasColumn('registration_pricing_tiers', 'reference_usd_per_exam')) {
            Schema::table('registration_pricing_tiers', function (Blueprint $table): void {
                $table->unsignedSmallInteger('reference_usd_per_exam')->nullable()->after('exam_count');
            });
        }

        $referenceUsd = [500, 475, 450, 425, 400, 375, 375, 375, 375, 375];

        foreach ($referenceUsd as $index => $amount) {
            DB::table('registration_pricing_tiers')
                ->where('exam_count', $index + 1)
                ->whereNull('reference_usd_per_exam')
                ->update(['reference_usd_per_exam' => $amount]);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('registration_pricing_tiers') && Schema::hasColumn('registration_pricing_tiers', 'reference_usd_per_exam')) {
            Schema::table('registration_pricing_tiers', function (Blueprint $table): void {
                $table->dropColumn('reference_usd_per_exam');
            });
        }
    }
};
