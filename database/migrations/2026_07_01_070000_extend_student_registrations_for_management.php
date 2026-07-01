<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->string('registration_period')->default('main')->after('status');
            $table->string('payment_status')->default('unpaid')->after('registration_period');
            $table->string('payment_method')->nullable()->after('payment_status');
            $table->string('payment_reference')->nullable()->after('payment_method');
            $table->timestamp('payment_date')->nullable()->after('payment_reference');
            $table->unsignedInteger('payment_amount')->default(0)->after('payment_date');
            $table->string('passport_upload_status')->default('not_uploaded')->after('passport_expiry_date');
            $table->string('verification_status')->default('unverified')->after('payment_amount');
            $table->foreignId('verified_by')->nullable()->after('verification_status')->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable()->after('verified_by');
            $table->text('verification_note')->nullable()->after('verified_at');
            $table->index(['status', 'payment_status', 'submitted_at'], 'student_regs_status_payment_submitted_idx');
            $table->index(['registration_period', 'verification_status'], 'student_regs_period_verify_idx');
        });

        Schema::create('registration_admin_notes', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('student_registration_id')->constrained()->cascadeOnDelete();
            $table->string('note_type')->default('general');
            $table->text('note');
            $table->boolean('is_pinned')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('registration_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('student_registration_id')->constrained()->cascadeOnDelete();
            $table->string('action');
            $table->string('field_name')->nullable();
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->text('reason')->nullable();
            $table->foreignId('performed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('performed_ip', 45)->nullable();
            $table->timestamp('performed_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_audit_logs');
        Schema::dropIfExists('registration_admin_notes');

        Schema::table('student_registrations', function (Blueprint $table) {
            $table->dropIndex('student_regs_status_payment_submitted_idx');
            $table->dropIndex('student_regs_period_verify_idx');
            $table->dropConstrainedForeignId('verified_by');
            $table->dropColumn([
                'registration_period',
                'payment_status',
                'payment_method',
                'payment_reference',
                'payment_date',
                'payment_amount',
                'passport_upload_status',
                'verification_status',
                'verified_at',
                'verification_note',
            ]);
        });
    }
};
