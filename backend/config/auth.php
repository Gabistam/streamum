<?php

return [
    'defaults' => [
        'guard' => 'api',
        'passwords' => 'api_users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'api_users',
        ],
        'api' => [
            'driver' => 'sanctum',
            'provider' => 'api_users',
        ],
    ],

    'providers' => [
        'api_users' => [
            'driver' => 'eloquent',
            'model' => App\Models\ApiUser::class,
        ],
    ],

    'passwords' => [
        'api_users' => [
            'provider' => 'api_users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];