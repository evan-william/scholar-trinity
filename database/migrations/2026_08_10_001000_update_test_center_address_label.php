<?php

use App\Models\LandingSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('landing_settings')) {
            return;
        }

        $setting = LandingSetting::query()
            ->where('group', 'copy')
            ->where('key', 'footer_office')
            ->first();

        if (! $setting) {
            return;
        }

        $value = is_array($setting->value) ? $setting->value : [];

        if (in_array($value['en'] ?? null, [null, '', 'Office Address'], true)) {
            $value['en'] = 'Test Center Address';
        }

        if (in_array($value['zh_TW'] ?? null, [null, '', '服務說明'], true)) {
            $value['zh_TW'] = '考場地址';
        }

        if (in_array($value['text'] ?? null, [null, '', 'Office Address'], true)) {
            $value['text'] = 'Test Center Address';
        }

        $setting->forceFill(['value' => $value])->save();
    }

    public function down(): void
    {
        // Client-facing copy updates are intentionally retained on rollback.
    }
};
