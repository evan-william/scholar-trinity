<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->unsignedInteger('grand_total')->default(0)->after('total_fee');
            $table->string('currency', 8)->default('NTD')->after('grand_total');
            $table->timestamp('fee_snapshot_at')->nullable()->after('currency');
        });

        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('provider')->default('manual');
            $table->string('mode')->default('sandbox');
            $table->string('merchant_id')->nullable();
            $table->text('hash_key_encrypted')->nullable();
            $table->text('hash_iv_encrypted')->nullable();
            $table->string('callback_url')->nullable();
            $table->string('return_url')->nullable();
            $table->string('success_url')->nullable();
            $table->string('failed_url')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_code')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->text('manual_instruction')->nullable();
            $table->unsignedTinyInteger('payment_deadline_days')->default(7);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('registration_payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('student_registration_id')->constrained()->cascadeOnDelete();
            $table->string('payment_reference')->unique();
            $table->string('provider')->default('manual');
            $table->string('payment_method')->default('manual_bank_transfer');
            $table->string('payment_status')->default('pending');
            $table->unsignedInteger('exam_fee_amount')->default(0);
            $table->unsignedInteger('service_fee_amount')->default(0);
            $table->unsignedInteger('late_fee_amount')->default(0);
            $table->unsignedInteger('grand_total')->default(0);
            $table->string('currency', 8)->default('NTD');
            $table->string('transaction_id')->nullable();
            $table->string('gateway_order_id')->nullable()->unique();
            $table->json('gateway_payload')->nullable();
            $table->string('proof_file_path')->nullable();
            $table->string('proof_original_name')->nullable();
            $table->string('proof_mime_type')->nullable();
            $table->unsignedBigInteger('proof_file_size')->nullable();
            $table->timestamp('proof_uploaded_at')->nullable();
            $table->timestamp('payment_deadline_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('rejected_reason')->nullable();
            $table->timestamps();
            $table->index(['payment_status', 'payment_method']);
        });

        Schema::create('payment_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('registration_payment_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('student_registration_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('event_type');
            $table->string('old_status')->nullable();
            $table->string('new_status')->nullable();
            $table->json('payload')->nullable();
            $table->foreignId('performed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('performed_ip', 45)->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_logs');
        Schema::dropIfExists('registration_payments');
        Schema::dropIfExists('payment_settings');

        Schema::table('student_registrations', function (Blueprint $table) {
            $table->dropColumn(['grand_total', 'currency', 'fee_snapshot_at']);
        });
    }
};
