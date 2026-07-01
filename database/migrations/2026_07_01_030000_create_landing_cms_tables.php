<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('landing_settings', function (Blueprint $table) {
            $table->id();
            $table->string('group');
            $table->string('key');
            $table->json('value');
            $table->timestamps();
            $table->unique(['group', 'key']);
        });

        Schema::create('landing_sections', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('eyebrow')->nullable();
            $table->string('title');
            $table->text('body');
            $table->json('items')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('landing_timelines', function (Blueprint $table) {
            $table->id();
            $table->string('round');
            $table->string('month');
            $table->string('status');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('landing_fees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('currency', 8)->default('NTD');
            $table->unsignedInteger('amount')->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('landing_required_documents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_required')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('landing_faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('landing_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('organization');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('office_hours')->nullable();
            $table->text('address')->nullable();
            $table->string('map_url')->nullable();
            $table->json('social_links')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_contacts');
        Schema::dropIfExists('landing_faqs');
        Schema::dropIfExists('landing_required_documents');
        Schema::dropIfExists('landing_fees');
        Schema::dropIfExists('landing_timelines');
        Schema::dropIfExists('landing_sections');
        Schema::dropIfExists('landing_settings');
    }
};
