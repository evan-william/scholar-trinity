<?php

return [
    'session_timeout_minutes' => (int) env('ADMIN_SESSION_TIMEOUT_MINUTES', 30),
    'login_username' => env('ADMIN_LOGIN_USERNAME', 'admin'),
    'login_email' => env('ADMIN_LOGIN_EMAIL', 'admin@trinityscholar.local'),
    'bootstrap_password' => env('ADMIN_BOOTSTRAP_PASSWORD', 'admin123'),
];
