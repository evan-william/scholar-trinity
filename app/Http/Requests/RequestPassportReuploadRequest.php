<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestPassportReuploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return [
            'reason' => ['required', 'string', 'max:1000'],
            'deadline' => ['required', 'date', 'after:today'],
        ];
    }
}
