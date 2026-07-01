<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_seasons', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('season_name');
            $table->string('academic_year');
            $table->unsignedSmallInteger('exam_year');
            $table->timestamp('main_registration_start_at')->nullable();
            $table->timestamp('main_registration_end_at')->nullable();
            $table->timestamp('late_registration_start_at')->nullable();
            $table->timestamp('late_registration_end_at')->nullable();
            $table->string('timezone')->default('Asia/Taipei');
            $table->string('currency', 8)->default('NTD');
            $table->unsignedInteger('default_service_fee')->default(0);
            $table->unsignedInteger('default_late_fee')->default(0);
            $table->string('status')->default('draft');
            $table->boolean('is_active')->default(false);
            $table->text('public_status_message')->nullable();
            $table->text('close_reason')->nullable();
            $table->text('reopen_reason')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('cloned_from_id')->nullable()->constrained('exam_seasons')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('archived_at')->nullable();
            $table->foreignId('archived_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['exam_year', 'status']);
            $table->index(['is_active', 'status']);
        });

        Schema::table('ap_exam_subjects', function (Blueprint $table) {
            $table->foreignId('exam_season_id')->nullable()->after('uuid')->constrained('exam_seasons')->nullOnDelete();
            $table->index(['exam_season_id', 'status', 'is_active'], 'ap_subjects_season_status_active_idx');
        });

        Schema::table('student_registrations', function (Blueprint $table) {
            $table->foreignId('exam_season_id')->nullable()->after('id')->constrained('exam_seasons')->nullOnDelete();
            $table->string('registration_period_type')->nullable()->after('status');
            $table->index(['exam_season_id', 'status'], 'student_regs_season_status_idx');
            $table->index(['registration_period_type', 'submitted_at'], 'student_regs_period_type_submitted_idx');
        });
    }

    public function down(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->dropIndex('student_regs_season_status_idx');
            $table->dropIndex('student_regs_period_type_submitted_idx');
            $table->dropConstrainedForeignId('exam_season_id');
            $table->dropColumn('registration_period_type');
        });

        Schema::table('ap_exam_subjects', function (Blueprint $table) {
            $table->dropIndex('ap_subjects_season_status_active_idx');
            $table->dropConstrainedForeignId('exam_season_id');
        });

        Schema::dropIfExists('exam_seasons');
    }
};
