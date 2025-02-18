<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],  // Add API and CSRF token routes

    'allowed_origins' => ['http://10.109.2.112', 'https://10.109.2.112', 'https://10.109.2.112:8081', 'http://10.109.2.112:8081', 'http://127.0.0.1', 'http://127.0.0.1:8000', 'http://127.0.0.1:8080', 'http://localhost:8080', 'http://localhost:8000', 'http://localhost'],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE'],
    'allowed_headers' => ['X-CSRF-TOKEN', 'Authorization', 'Content-Type'],

    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,  // Allow cookies to be sent
];
