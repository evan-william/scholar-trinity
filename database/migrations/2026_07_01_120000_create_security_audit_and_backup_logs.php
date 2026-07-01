<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('security_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('module')->index();
            $table->string('event_type')->index();
            $table->string('action');
            $table->nullableMorphs('auditable');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('user_type')->nullable();
            $table->string('ip_address', 45)->nullable()->index();
            $table->text('user_agent')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->json('metadata')->nullable();
            $table->string('status')->default('success')->index();
            $table->timestamp('created_at')->nullable()->index();
        });

        Schema::create('backup_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('backup_type')->default('database');
            $table->string('status')->default('pending');
            $table->string('disk')->default('local');
            $table->string('path')->nullable();
            $table->unsignedBigInteger('size_bytes')->nullable();
            $table->text('message')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('backup_logs');
        Schema::dropIfExists('security_audit_logs');
    }
};
