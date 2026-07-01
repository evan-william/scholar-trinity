<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ap_exam_subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->date('exam_date')->nullable();
            $table->unsignedInteger('exam_fee')->default(0);
            $table->unsignedInteger('service_fee')->default(0);
            $table->string('status')->default('open');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('student_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique();
            $table->string('status')->default('submitted');
            $table->string('student_full_name');
            $table->string('preferred_name')->nullable();
            $table->string('gender')->nullable();
            $table->date('date_of_birth');
            $table->string('nationality');
            $table->string('passport_number');
            $table->date('passport_expiry_date')->nullable();
            $table->string('student_email');
            $table->string('student_phone')->nullable();
            $table->string('school_name');
            $table->string('school_country');
            $table->string('school_city')->nullable();
            $table->string('grade_level');
            $table->unsignedSmallInteger('graduation_year')->nullable();
            $table->unsignedInteger('exam_fee_total')->default(0);
            $table->unsignedInteger('service_fee_total')->default(0);
            $table->unsignedInteger('late_fee_total')->default(0);
            $table->unsignedInteger('total_fee')->default(0);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['student_email', 'passport_number']);
        });

        Schema::create('registration_exam_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_registration_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ap_exam_subject_id')->constrained()->restrictOnDelete();
            $table->string('subject_name');
            $table->date('exam_date')->nullable();
            $table->unsignedInteger('exam_fee');
            $table->unsignedInteger('service_fee');
            $table->timestamps();
            $table->unique(['student_registration_id', 'ap_exam_subject_id'], 'registration_subject_unique');
        });

        Schema::create('registration_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_registration_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('parent_full_name');
            $table->string('relationship');
            $table->string('parent_email');
            $table->string('parent_phone');
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_phone');
            $table->string('emergency_contact_relationship');
            $table->timestamps();
        });

        Schema::create('registration_agreements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_registration_id')->constrained()->cascadeOnDelete();
            $table->string('agreement_key');
            $table->timestamp('accepted_at');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            $table->unique(['student_registration_id', 'agreement_key'], 'registration_agreement_unique');
        });

        Schema::create('registration_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_registration_id')->constrained()->cascadeOnDelete();
            $table->string('from_status')->nullable();
            $table->string('to_status');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_status_histories');
        Schema::dropIfExists('registration_agreements');
        Schema::dropIfExists('registration_contacts');
        Schema::dropIfExists('registration_exam_subjects');
        Schema::dropIfExists('student_registrations');
        Schema::dropIfExists('ap_exam_subjects');
    }
};
