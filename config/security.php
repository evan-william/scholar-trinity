<?php

return [
    'force_https' => env('SECURITY_FORCE_HTTPS', false),
    'secure_admin_prefix' => env('ADMIN_ROUTE_PREFIX', 'admin'),
    'file_max_kb' => (int) env('SECURITY_FILE_MAX_KB', 5120),
    'allowed_file_mimes' => ['application/pdf', 'image/jpeg', 'image/png'],
    'allowed_file_extensions' => ['pdf', 'jpg', 'jpeg', 'png'],
    'backup_retention_days' => (int) env('SECURITY_BACKUP_RETENTION_DAYS', 7),
    'audit_sensitive_keys' => [
        'password',
        'password_confirmation',
        'token',
        'hash_key',
        'hash_iv',
        'api_key',
        'api_key_encrypted',
        'hash_key_encrypted',
        'hash_iv_encrypted',
        'passport_file_path',
        'proof_file_path',
        'gateway_payload',
    ],
];
