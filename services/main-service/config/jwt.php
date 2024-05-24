<?php

return [
    // JWT Authentication Secret (php artisan jwt:secret)
    'secret' => env('JWT_SECRET'),

    // JWT Authentication Keys
    'keys' => [
        'public' => env('JWT_PUBLIC_KEY'),
        'private' => env('JWT_PRIVATE_KEY'),
        'passphrase' => env('JWT_PASSPHRASE'),

    ],

    // Specify the length of time (in minutes) that the token will be valid for
    'ttl' => env('JWT_TTL', 20160),

    // Refresh time to live
    'refresh_ttl' => env('JWT_REFRESH_TTL', 20160),

    'algo' => env('JWT_ALGO', Tymon\JWTAuth\Providers\JWT\Provider::ALGO_HS256),

    // Required Claims
    // A TokenInvalidException will be thrown if any of these claims are not present in the payload.
    'required_claims' => [
        'iss',
        'iat',
        'exp',
        'nbf',
        'sub',
        'jti',
    ],

    // Persistent Claims
    'persistent_claims' => [
        // 'foo',
        // 'bar',
    ],

    'lock_subject' => true,

    'leeway' => env('JWT_LEEWAY', 0),

    'blacklist_enabled' => env('JWT_BLACKLIST_ENABLED', true),

    'blacklist_grace_period' => env('JWT_BLACKLIST_GRACE_PERIOD', 0),

    'decrypt_cookies' => false,

    'providers' => [
        'jwt' => Tymon\JWTAuth\Providers\JWT\Lcobucci::class,
        'auth' => Tymon\JWTAuth\Providers\Auth\Illuminate::class,
        'storage' => Tymon\JWTAuth\Providers\Storage\Illuminate::class,
    ],
];
