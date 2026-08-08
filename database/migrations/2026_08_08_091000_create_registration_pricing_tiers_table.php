<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registration_pricing_tiers', function (Blueprint $table): void {
            $table->id();
            $table->unsignedTinyInteger('exam_count')->unique();
            $table->unsignedInteger('combined_fee_per_exam');
            $table->unsignedInteger('exam_fee_per_exam');
            $table->unsignedInteger('service_fee_per_exam');
            $table->string('currency', 8)->default('NTD');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $combinedFees = [17500, 16700, 15900, 15100, 14300, 13500, 13500, 13500, 13500, 13500];

        foreach ($combinedFees as $index => $combinedFee) {
            DB::table('registration_pricing_tiers')->insert([
                'exam_count' => $index + 1,
                'combined_fee_per_exam' => $combinedFee,
                'exam_fee_per_exam' => 7800,
                'service_fee_per_exam' => $combinedFee - 7800,
                'currency' => 'NTD',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_pricing_tiers');
    }
};
