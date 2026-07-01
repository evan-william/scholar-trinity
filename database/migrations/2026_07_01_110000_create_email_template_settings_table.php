<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_template_settings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('template_key');
            $table->string('locale', 8);
            $table->string('subject');
            $table->text('body_html');
            $table->text('body_text')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['template_key', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_template_settings');
    }
};
