<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('landing_settings')) {
            return;
        }

        $copyGroups = require config_path('landing_copy.php');

        foreach ($copyGroups as $group) {
            foreach ($group['fields'] as $key => $copy) {
                if (DB::table('landing_settings')->where('group', 'copy')->where('key', $key)->exists()) {
                    continue;
                }

                DB::table('landing_settings')->insert([
                    'group' => 'copy',
                    'key' => $key,
                    'value' => json_encode([
                        'text' => $copy['en'],
                        'en' => $copy['en'],
                        'zh_TW' => $copy['zh_TW'],
                    ], JSON_UNESCAPED_UNICODE),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        // Content is intentionally retained so a rollback never deletes client edits.
    }
};
