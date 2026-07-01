<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->uuid('passport_document_uuid')->nullable()->unique()->after('passport_upload_status');
            $table->string('passport_file_path')->nullable()->after('passport_document_uuid');
            $table->string('passport_original_name')->nullable()->after('passport_file_path');
            $table->string('passport_mime_type')->nullable()->after('passport_original_name');
            $table->unsignedBigInteger('passport_file_size')->nullable()->after('passport_mime_type');
            $table->timestamp('passport_uploaded_at')->nullable()->after('passport_file_size');
            $table->foreignId('passport_uploaded_by')->nullable()->after('passport_uploaded_at')->constrained('users')->nullOnDelete();
            $table->timestamp('passport_last_viewed_at')->nullable()->after('passport_uploaded_by');
            $table->foreignId('passport_last_viewed_by')->nullable()->after('passport_last_viewed_at')->constrained('users')->nullOnDelete();
            $table->timestamp('passport_last_downloaded_at')->nullable()->after('passport_last_viewed_by');
            $table->foreignId('passport_last_downloaded_by')->nullable()->after('passport_last_downloaded_at')->constrained('users')->nullOnDelete();
            $table->timestamp('passport_replaced_at')->nullable()->after('passport_last_downloaded_by');
            $table->foreignId('passport_replaced_by')->nullable()->after('passport_replaced_at')->constrained('users')->nullOnDelete();
            $table->text('passport_replacement_reason')->nullable()->after('passport_replaced_by');
            $table->timestamp('passport_verified_at')->nullable()->after('passport_replacement_reason');
            $table->foreignId('passport_verified_by')->nullable()->after('passport_verified_at')->constrained('users')->nullOnDelete();
            $table->text('passport_verification_note')->nullable()->after('passport_verified_by');
            $table->timestamp('passport_invalid_at')->nullable()->after('passport_verification_note');
            $table->foreignId('passport_invalid_by')->nullable()->after('passport_invalid_at')->constrained('users')->nullOnDelete();
            $table->text('passport_invalid_reason')->nullable()->after('passport_invalid_by');
            $table->timestamp('passport_reupload_requested_at')->nullable()->after('passport_invalid_reason');
            $table->timestamp('passport_reupload_deadline_at')->nullable()->after('passport_reupload_requested_at');
            $table->text('passport_reupload_reason')->nullable()->after('passport_reupload_deadline_at');
        });

        Schema::create('registration_export_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('export_type');
            $table->string('export_format');
            $table->string('file_name');
            $table->string('storage_disk')->default('local');
            $table->string('storage_path');
            $table->json('filter_payload')->nullable();
            $table->unsignedInteger('record_count')->default(0);
            $table->foreignId('exported_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('exported_ip', 45)->nullable();
            $table->timestamp('exported_at');
            $table->timestamp('expires_at')->nullable();
            $table->string('status')->default('completed');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_export_logs');

        Schema::table('student_registrations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('passport_uploaded_by');
            $table->dropConstrainedForeignId('passport_last_viewed_by');
            $table->dropConstrainedForeignId('passport_last_downloaded_by');
            $table->dropConstrainedForeignId('passport_replaced_by');
            $table->dropConstrainedForeignId('passport_verified_by');
            $table->dropConstrainedForeignId('passport_invalid_by');
            $table->dropColumn([
                'passport_document_uuid',
                'passport_file_path',
                'passport_original_name',
                'passport_mime_type',
                'passport_file_size',
                'passport_uploaded_at',
                'passport_last_viewed_at',
                'passport_last_downloaded_at',
                'passport_replaced_at',
                'passport_replacement_reason',
                'passport_verified_at',
                'passport_verification_note',
                'passport_invalid_at',
                'passport_invalid_reason',
                'passport_reupload_requested_at',
                'passport_reupload_deadline_at',
                'passport_reupload_reason',
            ]);
        });
    }
};
