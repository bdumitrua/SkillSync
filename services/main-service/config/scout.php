<?php

return [
    // Supported: "algolia", "meilisearch", "database", "collection", "null"
    'driver' => env('SCOUT_DRIVER', 'elastic'),

    'prefix' => env('SCOUT_PREFIX', ''),

    // Queue Data Syncing
    'queue' => env('SCOUT_QUEUE', false),

    // Database Transactions
    'after_commit' => false,

    // Chunk Sizes
    'chunk' => [
        'searchable' => 500,
        'unsearchable' => 500,
    ],

    // Soft Deletes
    'soft_delete' => false,

    // Identify User
    // Supported engines: "algolia"
    'identify' => env('SCOUT_IDENTIFY', false),

    // 'elasticsearch' => [
    //     'hosts' => env('ELASTICSEARCH_HOST', 'http://elasticsearch:9200'),
    // ],
];
