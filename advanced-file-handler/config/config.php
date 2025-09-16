<?php

// config/config.php

/**
 * Application Configuration
 *
 * This file returns an array of configuration settings for the application.
 * Using a configuration file like this allows for easy management of settings
 * across different environments without changing the core application code.
 */

return [
    'uploads_directory' => __DIR__ . '/../uploads/',
    'max_file_size' => 5 * 1024 * 1024, // 5 MB
    'allowed_mime_types' => [
        'image/jpeg',
        'image/png',
        'image/gif',
        'application/pdf',
        'text/plain'
    ]
];
