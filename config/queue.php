<?php
return [
    'default' => env('QUEUE_DRIVER', 'database'),

    'connections' => [
        'sync'       => [
            'driver' => 'sync',
        ],
        'database'   => [
            'driver' => 'database',
            'table'  => 'queue_jobs',
            'queue'  => 'default',
            'expire' => 60,
        ],
        'beanstalkd' => [
            'driver' => 'beanstalkd',
            'host'   => 'localhost',
            'queue'  => 'app-api',
            'ttr'    => 60,
        ],
        'redis'      => [
            'driver'     => 'redis',
            'connection' => 'default',
            'queue'      => 'default',
            'expire'     => 60,
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Failed Queue Jobs
    |--------------------------------------------------------------------------
    |
    | These options configure the behavior of failed queue job logging so you
    | can control which database and table are used to store the jobs that
    | have failed. You may change them to any database / table you wish.
    |
    */
    'failed'      => [
        'database' => env('DB_CONNECTION', 'mysql'),
        'table'    => 'FailedAppJobs',
    ],
];
