<?php

return [
    // Default Hash Driver
    // Supported: "bcrypt", "argon", "argon2id"
    'driver' => 'bcrypt',

    // Bcrypt Options
    'bcrypt' => [
        'rounds' => env('BCRYPT_ROUNDS', 10),
    ],

    // Argon Options
    'argon' => [
        'memory' => 65536,
        'threads' => 1,
        'time' => 4,
    ],

];
