<?php

namespace Database\Seeders;

use App\Models\PaymentSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PaymentSettingSeeder extends Seeder
{
    public function run(): void
    {
        $setting = PaymentSetting::query()->firstOrNew(['provider' => 'manual', 'mode' => 'sandbox']);
        $setting->fill([
            'uuid' => $setting->uuid ?: (string) Str::uuid(),
            'merchant_id' => 'SANDBOX',
            'callback_url' => url('/payments/gateway/callback'),
            'return_url' => url('/'),
            'bank_name' => '臺灣銀行',
            'bank_code' => '004',
            'bank_branch' => '松山分行',
            'account_name' => '力可科技股份有限公司',
            'account_number' => '064001061782',
            'manual_instruction' => 'Please include your AP registration reference number in the transfer note and send the transfer receipt by email or Line for manual verification.',
            'payment_deadline_days' => 7,
            'is_active' => true,
        ]);
        $setting->setHashKey('sandbox-key');
        $setting->setHashIv('sandbox-iv');
        $setting->save();
    }
}
