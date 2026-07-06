<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_notifications', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('type', 80);
            $table->string('severity', 30)->default('info');
            $table->string('title');
            $table->text('body')->nullable();
            $table->string('link_url')->nullable();
            $table->foreignId('student_registration_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('registration_payment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('receipt_request_id')->nullable()->constrained()->nullOnDelete();
            $table->json('payload')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->index(['read_at', 'created_at']);
            $table->index(['type', 'severity']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_notifications');
    }
};
