<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('landing_sections')) {
            return;
        }

        $section = DB::table('landing_sections')->where('key', 'registration_intro')->first();

        if (! $section) {
            return;
        }

        $updates = [];

        if ($section->title === '2026 AP Exam Registration') {
            $updates['title'] = '2027 AP Exam Registration';
        }

        $items = json_decode((string) $section->items, true);
        if (is_array($items)) {
            $refreshedItems = array_map(
                fn ($item) => is_string($item)
                    ? str_replace(
                        ['January through March', 'January - March'],
                        ['mid November through mid March', 'Mid November - Mid March'],
                        $item
                    )
                    : $item,
                $items
            );

            if ($refreshedItems !== $items) {
                $updates['items'] = json_encode($refreshedItems);
            }
        }

        if ($updates !== []) {
            DB::table('landing_sections')
                ->where('key', 'registration_intro')
                ->update($updates + ['updated_at' => now()]);
        }
    }

    public function down(): void
    {
        // Preserve the current registration copy during rollback.
    }
};
