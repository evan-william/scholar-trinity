<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('student_registrations', 'primacy_email_opt_in')) {
            Schema::table('student_registrations', function (Blueprint $table): void {
                $table->boolean('primacy_email_opt_in')->default(false)->after('preparation_interest');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('student_registrations', 'primacy_email_opt_in')) {
            Schema::table('student_registrations', function (Blueprint $table): void {
                $table->dropColumn('primacy_email_opt_in');
            });
        }
    }
};
