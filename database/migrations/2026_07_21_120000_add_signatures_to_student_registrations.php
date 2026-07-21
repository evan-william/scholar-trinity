<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_registrations', function (Blueprint $table): void {
            $table->string('student_signature_name', 140)->nullable()->after('preparation_notes');
            $table->date('student_signature_date')->nullable()->after('student_signature_name');
            $table->string('guardian_signature_name', 140)->nullable()->after('student_signature_date');
            $table->date('guardian_signature_date')->nullable()->after('guardian_signature_name');
        });
    }

    public function down(): void
    {
        Schema::table('student_registrations', function (Blueprint $table): void {
            $table->dropColumn([
                'student_signature_name',
                'student_signature_date',
                'guardian_signature_name',
                'guardian_signature_date',
            ]);
        });
    }
};
