<?php

return [
    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
        'csrf-cookie',
        'login',
        'logout',
        'register',
        'password/*',
        'admin_lapangan',
        'admin_lapangan/*',
    ],

    'allowed_methods' => ['*'],

    // Ganti origin sesuai URL dev FE
    'allowed_origins' => [
        'http://localhost:5173',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => ['XSRF-TOKEN'],

    'max_age' => 0,

    'supports_credentials' => true,
];


