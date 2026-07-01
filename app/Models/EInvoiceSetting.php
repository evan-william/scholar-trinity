<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class EInvoiceSetting extends Model
{
    protected $fillable = [
        'uuid',
        'provider',
        'environment',
        'merchant_id',
        'api_key_encrypted',
        'hash_key_encrypted',
        'hash_iv_encrypted',
        'callback_url',
        'late_fee_taxable',
        'allow_unpaid_receipts',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'late_fee_taxable' => 'boolean',
        'allow_unpaid_receipts' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (EInvoiceSetting $setting): void {
            $setting->uuid ??= (string) Str::uuid();
        });
    }

    public function setApiKey(?string $value): void
    {
        if ($value !== null && $value !== '') {
            $this->api_key_encrypted = Crypt::encryptString($value);
        }
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
}
