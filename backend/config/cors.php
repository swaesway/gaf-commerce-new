<?php

return [

    /*
        |--------------------------------------------------------------------------
        | Cross-Origin Resource Sharing (CORS) Configuration
        |--------------------------------------------------------------------------
        |
        | This configuration defines how your application handles cross-origin
        | requests. Adjust these settings as needed for your environment.
        |
        */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],

    'allowed_origins' => [
        "http://localhost:3000",
        "http://localhost:5173",
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['Content-Type', 'X-Requested-With', 'Authorization'],

    'exposed_headers' => [],

    'max_age' => 3600, // Cache preflight response for 1 hour

    'supports_credentials' => true, // Required for cookies or HTTP auth in CORS requests
];
