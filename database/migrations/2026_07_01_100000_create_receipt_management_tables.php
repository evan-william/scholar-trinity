<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receipt_requests', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('student_registration_id')->constrained()->cascadeOnDelete();
            $table->foreignId('registration_payment_id')->nullable()->constrained()->nullOnDelete();
            $table->string('buyer_name')->nullable();
            $table->string('buyer_email')->nullable();
            $table->string('buyer_phone')->nullable();
            $table->string('company_name')->nullable();
            $table->string('gui_tax_id', 20)->nullable();
            $table->string('receipt_type')->default('none');
            $table->unsignedInteger('exam_fee_amount')->default(0);
            $table->unsignedInteger('service_fee_amount')->default(0);
            $table->unsignedInteger('late_fee_amount')->default(0);
            $table->unsignedInteger('taxable_receipt_amount')->default(0);
            $table->unsignedInteger('non_receipt_amount')->default(0);
            $table->string('currency', 8)->default('NTD');
            $table->string('status')->default('not_requested');
            $table->string('receipt_number')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['status', 'receipt_type']);
        });

        Schema::create('receipt_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('receipt_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_registration_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('event_type');
            $table->string('old_status')->nullable();
            $table->string('new_status')->nullable();
            $table->json('payload')->nullable();
            $table->foreignId('performed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('performed_ip', 45)->nullable();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('e_invoice_settings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('provider')->default('manual');
            $table->string('environment')->default('sandbox');
            $table->string('merchant_id')->nullable();
            $table->text('api_key_encrypted')->nullable();
            $table->text('hash_key_encrypted')->nullable();
            $table->text('hash_iv_encrypted')->nullable();
            $table->string('callback_url')->nullable();
            $table->boolean('late_fee_taxable')->default(false);
            $table->boolean('allow_unpaid_receipts')->default(false);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('e_invoice_transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('receipt_request_id')->constrained()->cascadeOnDelete();
            $table->string('provider')->default('manual');
            $table->string('provider_invoice_number')->nullable();
            $table->string('provider_random_code')->nullable();
            $table->string('provider_transaction_id')->nullable();
            $table->string('provider_status')->default('pending');
            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('emailed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('e_invoice_transactions');
        Schema::dropIfExists('e_invoice_settings');
        Schema::dropIfExists('receipt_logs');
        Schema::dropIfExists('receipt_requests');
    }
};
