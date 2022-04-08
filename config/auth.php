<?php
return [
    'defaults' => [
        'guard' => 'api',
    ],
    'guards'   => [
        'api' => [
            'driver'   => 'api',
            'provider' => 'api',
        ],
    ],
    // 'providers' => [
    //     'api' => [
    //         'driver' => 'eloquent',
    //         'model'  => \App\Models\User::class,
    //     ],
    // ],
];