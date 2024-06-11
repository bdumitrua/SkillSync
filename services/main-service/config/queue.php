<?php

return [
    'default' => env('QUEUE_CONNECTION', 'redis'),

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

    'redis' => [
        'driver' => 'redis',
        'connection' => env('REDIS_QUEUE_CONNECTION', 'default'),
        'queue' => env('REDIS_QUEUE', '{default}'),
        'retry_after' => env('REDIS_QUEUE_RETRY_AFTER', 90),
        'block_for' => 3,
        'after_commit' => false,
    ],
];
