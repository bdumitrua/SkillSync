<?php

return [
    'default' => env('QUEUE_CONNECTION', 'sync'),

    // Drivers: "sync", "database", "beanstalkd", "sqs", "redis", "null"
    'connections' => [
        'sync' => [
            'driver' => 'sync',
        ],
    ],

    'batching' => [
        'database' => env('DB_CONNECTION', 'mysql'),
        'table' => 'job_batches',
    ],

    'failed' => [
        'driver' => env('QUEUE_FAILED_DRIVER', 'database-uuids'),
        'database' => env('DB_CONNECTION', 'mysql'),
        'table' => 'failed_jobs',
    ],

];
