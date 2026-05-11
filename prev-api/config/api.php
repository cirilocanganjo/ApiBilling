<?php

return [
    // Rate limit da API
    'rate_limit' => env('API_RATE_LIMIT', 500),  // máximo de requisições
    'rate_limit_minutes' => env('API_RATE_LIMIT_PER_MINUTE', 1), // janela em minutos

    // Token expiration
    'token_expiration_minutes' => env('SANCTUM_TOKEN_EXPIRATION', 120),
];
