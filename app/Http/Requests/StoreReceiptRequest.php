<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReceiptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'receipt_type' => ['required', 'in:none,personal,company,donation'],
            'buyer_name' => ['required_unless:receipt_type,none', 'nullable', 'string', 'max:140'],
            'buyer_email' => ['required_unless:receipt_type,none', 'nullable', 'email', 'max:160'],
            'buyer_phone' => ['required_unless:receipt_type,none', 'nullable', 'string', 'max:40', 'regex:/^\\+?[0-9\\s().-]{6,40}$/'],
            'company_name' => ['required_if:receipt_type,company', 'nullable', 'string', 'max:160'],
            'gui_tax_id' => ['required_if:receipt_type,company', 'nullable', 'digits:8', function (string $attribute, mixed $value, \Closure $fail): void {
                if ($value && ! $this->validTaiwanGui((string) $value)) {
                    $fail(__('receipt.validation.gui_tax_id'));
                }
            }],
        ];
    }

    private function validTaiwanGui(string $gui): bool
    {
        if (! preg_match('/^[0-9]{8}$/', $gui)) {
            return false;
        }

        $weights = [1, 2, 1, 2, 1, 2, 4, 1];
        $sum = 0;
        foreach (str_split($gui) as $index => $digit) {
            $product = ((int) $digit) * $weights[$index];
            $sum += intdiv($product, 10) + ($product % 10);
        }

        return $sum % 10 === 0 || ((int) $gui[6] === 7 && ($sum + 1) % 10 === 0);
    }
}
