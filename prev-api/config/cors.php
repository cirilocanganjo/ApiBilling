<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cross-origin resource sharing (CORS) configuration
    |--------------------------------------------------------------------------
    |
    | This file reads allowed origins from the .env (ALLOWED_ORIGINS) so you
    | can add local urls (http://localhost, https://project.test, etc) and
    | production domain(s) when you deploy.
    |
    */

    'paths' => ['api/*'],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    // Read allowed origins from .env (comma separated). Trim values and remove empties.
    'allowed_origins' => array_values(array_filter(array_map('trim', explode(',', env('ALLOWED_ORIGINS', ''))))),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['Content-Type', 'Authorization', 'Accept', 'X-Requested-With'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,
];
