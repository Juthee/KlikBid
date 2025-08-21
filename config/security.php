<?php

return [
    // Password Security (ISO 27001 A.9.4.3)
    'password' => [
        'min_length' => 8,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_numbers' => true,
        'require_symbols' => true,
        'max_age_days' => 90,
        'history_count' => 5, // Prevent reusing last 5 passwords
    ],

    // Session Security (ISO 27001 A.13.2.1)
    'session' => [
        'lifetime' => 120, // 2 hours in minutes
        'expire_on_close' => true,
        'encrypt' => true,
        'http_only' => true,
        'secure' => env('APP_ENV') === 'production',
        'same_site' => 'strict',
    ],

    // File Upload Security (ISO 27001 A.13.2.2)
    'uploads' => [
        'max_file_size' => 5120, // 5MB in KB
        'allowed_mime_types' => [
            'image/jpeg',
            'image/png',
            'image/webp',
            'image/gif'
        ],
        'scan_for_malware' => true,
        'quarantine_suspicious' => true,
    ],
];
