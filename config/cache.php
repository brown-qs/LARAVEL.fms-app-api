<?php

return [
    'default' => env('CACHE_DRIVER', 'redis'),

    'stores' => [
        'redis' => [
            'driver'  => 'redis',
            'cluster' => false,
            'default' => [
                'host'     => env('REDIS_HOST', '127.0.0.1'),
                'port'     => 6379,
                'database' => env('REDIS_CACHE_DB', 0),
            ],
        ],
    ],
];
