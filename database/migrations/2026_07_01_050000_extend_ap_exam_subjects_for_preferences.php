<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ap_exam_subjects', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique()->after('id');
            $table->string('category')->default('General')->after('code');
            $table->text('description')->nullable()->after('category');
            $table->time('start_time')->nullable()->after('exam_date');
            $table->time('end_time')->nullable()->after('start_time');
            $table->string('timezone')->default('Asia/Taipei')->after('end_time');
            $table->string('location')->nullable()->after('timezone');
            $table->unsignedInteger('quota')->nullable()->after('location');
            $table->unsignedInteger('registered_count')->default(0)->after('quota');
            $table->unsignedInteger('late_registration_fee')->default(0)->after('service_fee');
            $table->string('currency', 8)->default('NTD')->after('late_registration_fee');
            $table->timestamp('registration_open_at')->nullable()->after('status');
            $table->timestamp('registration_close_at')->nullable()->after('registration_open_at');
            $table->timestamp('late_registration_start_at')->nullable()->after('registration_close_at');
            $table->timestamp('late_registration_end_at')->nullable()->after('late_registration_start_at');
            $table->boolean('is_active')->default(true)->after('sort_order');
            $table->softDeletes();
        });

        Schema::table('registration_exam_subjects', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique()->after('id');
            $table->unsignedInteger('late_fee_snapshot')->default(0)->after('service_fee');
            $table->unsignedInteger('total_amount_snapshot')->default(0)->after('late_fee_snapshot');
            $table->string('currency_snapshot', 8)->default('NTD')->after('total_amount_snapshot');
            $table->timestamp('selected_at')->nullable()->after('currency_snapshot');
            $table->string('status')->default('selected')->after('selected_at');
        });
    }

    public function down(): void
    {
        Schema::table('registration_exam_subjects', function (Blueprint $table) {
            $table->dropColumn(['uuid', 'late_fee_snapshot', 'total_amount_snapshot', 'currency_snapshot', 'selected_at', 'status']);
        });

        Schema::table('ap_exam_subjects', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn([
                'uuid',
                'category',
                'description',
                'start_time',
                'end_time',
                'timezone',
                'location',
                'quota',
                'registered_count',
                'late_registration_fee',
                'currency',
                'registration_open_at',
                'registration_close_at',
                'late_registration_start_at',
                'late_registration_end_at',
                'is_active',
            ]);
        });
    }
};
