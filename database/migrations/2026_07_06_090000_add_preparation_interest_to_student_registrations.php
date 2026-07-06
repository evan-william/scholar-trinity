<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_registrations', function (Blueprint $table): void {
            if (! Schema::hasColumn('student_registrations', 'preparation_interest')) {
                $table->boolean('preparation_interest')->default(false)->after('practice_exam_total');
            }
            if (! Schema::hasColumn('student_registrations', 'group_class_interest')) {
                $table->boolean('group_class_interest')->default(false)->after('preparation_interest');
            }
            if (! Schema::hasColumn('student_registrations', 'private_tutoring_interest')) {
                $table->boolean('private_tutoring_interest')->default(false)->after('group_class_interest');
            }
            if (! Schema::hasColumn('student_registrations', 'preferred_tutoring_schedule')) {
                $table->string('preferred_tutoring_schedule')->nullable()->after('private_tutoring_interest');
            }
            if (! Schema::hasColumn('student_registrations', 'preferred_tutoring_language')) {
                $table->string('preferred_tutoring_language', 40)->nullable()->after('preferred_tutoring_schedule');
            }
            if (! Schema::hasColumn('student_registrations', 'preparation_notes')) {
                $table->text('preparation_notes')->nullable()->after('preferred_tutoring_language');
            }
        });
    }

    public function down(): void
    {
        Schema::table('student_registrations', function (Blueprint $table): void {
            foreach ([
                'preparation_notes',
                'preferred_tutoring_language',
                'preferred_tutoring_schedule',
                'private_tutoring_interest',
                'group_class_interest',
                'preparation_interest',
            ] as $column) {
                if (Schema::hasColumn('student_registrations', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
