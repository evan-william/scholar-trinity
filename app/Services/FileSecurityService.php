<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

class FileSecurityService
{
    public function validate(UploadedFile $file, string $field = 'file'): void
    {
        $name = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension());
        $mime = $file->getMimeType();
        $maxBytes = ((int) config('security.file_max_kb', 5120)) * 1024;

        if ($file->getSize() > $maxBytes) {
            throw ValidationException::withMessages([$field => 'File is larger than the configured secure upload limit.']);
        }

        if (! in_array($extension, config('security.allowed_file_extensions', []), true)) {
            throw ValidationException::withMessages([$field => 'Unsupported file extension.']);
        }

        if (! in_array($mime, config('security.allowed_file_mimes', []), true)) {
            throw ValidationException::withMessages([$field => 'Unsupported file type.']);
        }

        if (preg_match('/\.(php|phtml|exe|bat|cmd|js|sh|ps1)(\.|$)/i', $name) || substr_count($name, '.') > 1) {
            throw ValidationException::withMessages([$field => 'Suspicious file name rejected.']);
        }

        if (str_contains($name, '..') || str_contains($name, '/') || str_contains($name, '\\')) {
            throw ValidationException::withMessages([$field => 'Invalid file name.']);
        }
    }
}
