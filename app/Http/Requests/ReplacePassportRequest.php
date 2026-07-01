<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReplacePassportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return [
            'passport' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'reason' => ['required', 'string', 'max:500'],
        ];
    }
}
