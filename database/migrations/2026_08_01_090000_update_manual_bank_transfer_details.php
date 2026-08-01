<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('payment_settings')) {
            return;
        }

        $details = [
            'bank_name' => '臺灣銀行松山分行',
            'bank_code' => '004',
            'account_name' => '力可科技股份有限公司',
            'account_number' => '064001061782',
            'manual_instruction' => 'Please include your AP registration reference number in the transfer note and send the transfer receipt by email or Line for manual verification.',
            'payment_deadline_days' => 7,
            'updated_at' => now(),
        ];

        $legacy = DB::table('payment_settings')
            ->where('provider', 'manual')
            ->where(function ($query): void {
                $query->whereNull('bank_name')
                    ->orWhere('bank_name', 'Taiwan Bank Songshan Branch')
                    ->orWhere('account_number', '064001061782');
            });

        if ($legacy->exists()) {
            $legacy->update($details);

            return;
        }

        if (! DB::table('payment_settings')->where('is_active', true)->exists()) {
            DB::table('payment_settings')->insert($details + [
                'uuid' => (string) Str::uuid(),
                'provider' => 'manual',
                'mode' => 'sandbox',
                'merchant_id' => 'SANDBOX',
                'is_active' => true,
                'created_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Preserve administrator-edited payment information during rollback.
    }
};
