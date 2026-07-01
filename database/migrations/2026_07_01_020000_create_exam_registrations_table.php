<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();
            $table->string('registration_round');
            $table->string('student_family_name');
            $table->string('student_first_name');
            $table->string('student_middle_initial')->nullable();
            $table->string('student_middle_name')->nullable();
            $table->string('student_chinese_name');
            $table->string('student_class_name')->nullable();
            $table->string('grade');
            $table->string('school');
            $table->string('student_email');
            $table->string('student_phone');
            $table->string('passport_path');
            $table->string('passport_original_name');
            $table->string('passport_mime_type')->nullable();
            $table->unsignedBigInteger('passport_size');
            $table->string('parent_first_name');
            $table->string('parent_last_name');
            $table->string('parent_email');
            $table->string('parent_phone');
            $table->string('relationship');
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->string('city');
            $table->string('postal_code')->nullable();
            $table->string('country')->default('Taiwan');
            $table->json('selected_exams');
            $table->json('other_exams')->nullable();
            $table->unsignedInteger('regular_exam_count')->default(0);
            $table->unsignedInteger('practice_exam_count')->default(0);
            $table->unsignedInteger('exam_fee_total')->default(0);
            $table->unsignedInteger('practice_fee_total')->default(0);
            $table->unsignedInteger('late_fee_total')->default(0);
            $table->unsignedInteger('service_fee_total')->default(0);
            $table->unsignedInteger('total_due')->default(0);
            $table->boolean('needs_accommodations')->default(false);
            $table->string('ssd_code')->nullable();
            $table->string('accommodation_status')->nullable();
            $table->json('accommodations')->nullable();
            $table->string('payment_method');
            $table->string('receipt_type');
            $table->string('receipt_title')->nullable();
            $table->string('receipt_tax_id')->nullable();
            $table->string('receipt_email')->nullable();
            $table->timestamp('terms_accepted_at');
            $table->string('payment_status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_registrations');
    }
};
