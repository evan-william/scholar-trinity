<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class PaymentSetting extends Model
{
    protected $fillable = [
        'uuid',
        'provider',
        'mode',
        'merchant_id',
        'hash_key_encrypted',
        'hash_iv_encrypted',
        'callback_url',
        'return_url',
        'success_url',
        'failed_url',
        'bank_name',
        'bank_code',
        'account_name',
        'account_number',
        'manual_instruction',
        'payment_deadline_days',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'payment_deadline_days' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (PaymentSetting $setting): void {
            $setting->uuid ??= (string) Str::uuid();
        });
    }

    public function setHashKey(?string $value): void
    {
        if ($value !== null && $value !== '') {
            $this->hash_key_encrypted = Crypt::encryptString($value);
        }
    }

    public function setHashIv(?string $value): void
    {
        if ($value !== null && $value !== '') {
            $this->hash_iv_encrypted = Crypt::encryptString($value);
        }
    }

    public function hashKey(): ?string
    {
        return $this->hash_key_encrypted ? Crypt::decryptString($this->hash_key_encrypted) : null;
    }

    public function hashIv(): ?string
    {
        return $this->hash_iv_encrypted ? Crypt::decryptString($this->hash_iv_encrypted) : null;
    }
}
