<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegistrationAdminNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return [
            'note_type' => ['required', 'in:general,payment,document,student_contact,school_communication,issue,follow_up'],
            'note' => ['required', 'string', 'max:2000'],
            'is_pinned' => ['nullable', 'boolean'],
        ];
    }
}
