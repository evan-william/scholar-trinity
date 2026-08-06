<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('practice_exam_options', function (Blueprint $table): void {
            $table->unsignedInteger('fee')->default(2800)->change();
        });

        if (! Schema::hasColumn('practice_exam_options', 'seat_capacity')) {
            Schema::table('practice_exam_options', function (Blueprint $table): void {
                $table->unsignedSmallInteger('seat_capacity')->nullable()->after('fee');
            });
        }

        if (! Schema::hasColumn('registration_exam_selections', 'practice_exam_option_id')) {
            Schema::table('registration_exam_selections', function (Blueprint $table): void {
                $table->foreignId('practice_exam_option_id')
                    ->nullable()
                    ->after('ap_exam_subject_id')
                    ->constrained('practice_exam_options')
                    ->nullOnDelete();
            });
        }

        DB::table('practice_exam_options')->where('fee', 1800)->update(['fee' => 2800]);
    }

    public function down(): void
    {
        if (Schema::hasColumn('registration_exam_selections', 'practice_exam_option_id')) {
            Schema::table('registration_exam_selections', function (Blueprint $table): void {
                $table->dropConstrainedForeignId('practice_exam_option_id');
            });
        }

        if (Schema::hasColumn('practice_exam_options', 'seat_capacity')) {
            Schema::table('practice_exam_options', function (Blueprint $table): void {
                $table->dropColumn('seat_capacity');
            });
        }


        Schema::table('practice_exam_options', function (Blueprint $table): void {
            $table->unsignedInteger('fee')->default(1800)->change();
        });
    }
};
