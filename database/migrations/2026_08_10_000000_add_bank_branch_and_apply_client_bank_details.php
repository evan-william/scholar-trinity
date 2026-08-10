<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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

        if (! Schema::hasColumn('payment_settings', 'bank_branch')) {
            Schema::table('payment_settings', function (Blueprint $table): void {
                $table->string('bank_branch')->nullable()->after('bank_code');
            });
        }

        $details = [
            'bank_name' => '臺灣銀行',
            'bank_code' => '004',
            'bank_branch' => '松山分行',
            'account_name' => '力可科技股份有限公司',
            'account_number' => '064001061782',
            'updated_at' => now(),
        ];

        $target = DB::table('payment_settings')
            ->where('provider', 'manual')
            ->orWhere('is_active', true);

        if ($target->exists()) {
            $target->update($details);

            return;
        }

        DB::table('payment_settings')->insert($details + [
            'uuid' => (string) Str::uuid(),
            'provider' => 'manual',
            'mode' => 'sandbox',
            'merchant_id' => 'SANDBOX',
            'manual_instruction' => 'Please include your AP registration reference number in the transfer note and send the transfer receipt by email or Line for manual verification.',
            'payment_deadline_days' => 7,
            'is_active' => true,
            'created_at' => now(),
        ]);
    }

    public function down(): void
    {
        if (Schema::hasTable('payment_settings') && Schema::hasColumn('payment_settings', 'bank_branch')) {
            Schema::table('payment_settings', function (Blueprint $table): void {
                $table->dropColumn('bank_branch');
            });
        }
    }
};
