<?php

use Illuminate\Support\Str;

return [
    // Default Cache Store
    'default' => env('CACHE_DRIVER', 'array'),

    // Cache Stores
    // Supported drivers: "apc", "array", "database", "file",
    // |         "memcached", "redis", "dynamodb", "octane", "null"
    'stores' => [
        'redis' => [
            'driver' => 'redis',
            'connection' => 'cache',
            'lock_connection' => 'default',
        ],

        'file' => [
            'driver' => 'file',
            'path' => storage_path('framework/cache/data'),
            'lock_path' => storage_path('framework/cache/data'),
        ],

        'array' => [
            'driver' => 'array',
            'serialize' => false,
        ],
    ],

    // Cache Key Prefix
    'prefix' => env('CACHE_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_') . '_cache_'),

];
