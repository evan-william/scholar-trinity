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
            'bank_name' => 'Taiwan Bank Songshan Branch',
            'bank_code' => '004',
            'account_name' => 'Liko Technology Co., Ltd.',
            'account_number' => '064001061782',
            'manual_instruction' => 'Please include your AP registration reference number in the transfer note. Bank Name: Taiwan Bank Songshan Branch (004). Account Name: Liko Technology Co., Ltd. Account Number: 064001061782.',
            'payment_deadline_days' => 7,
            'is_active' => true,
        ]);
        $setting->setHashKey('sandbox-key');
        $setting->setHashIv('sandbox-iv');
        $setting->save();
    }
}
