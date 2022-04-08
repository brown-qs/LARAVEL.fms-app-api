<?php

return [
    'default'     => env('DB_CONNECTION', 'mysql'),
    'connections' => [
        'mysql' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST', 'localhost'),
            'database'  => env('DB_DATABASE', 'database'),
            'username'  => env('DB_USERNAME', 'root'),
            'password'  => env('DB_PASSWORD', 'test'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],
    ],
    'migrations'  => 'migrations',
    'fetch'       => PDO::FETCH_CLASS,

    'redis' => [
        'cluster' => false,
        'default' => [
            'host'     => env('REDIS_HOST', '127.0.0.1'),
            'port'     => 6379,
            'database' => 0,
        ],
    ],
];
