<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('system_settings')) {
            return;
        }

        DB::table('system_settings')
            ->where('key', 'registration.main_test_period')
            ->update([
                'value' => 'May 3-14, 2027',
                'updated_at' => now(),
            ]);

        DB::table('system_settings')
            ->where('key', 'registration.late_test_period')
            ->update([
                'value' => 'May 17 - 21, 2027',
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        // Keep the requested 2027 schedule values during rollback.
    }
};
