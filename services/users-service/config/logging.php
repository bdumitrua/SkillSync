<?php

return [
    // 'default' => env('LOG_CHANNEL', 'elasticsearch'),
    'default' => 'file',

    'deprecations' => [
        'channel' => env('LOG_DEPRECATIONS_CHANNEL', 'null'),
        'trace' => false,
    ],

    'channels' => [
        // 'elasticsearch' => [
        //     'driver' => 'monolog',
        //     'handler' => Monolog\Handler\ElasticsearchHandler::class,
        //     'handler_with' => [
        //         'client' => (new Elastic\Elasticsearch\ClientBuilder())->setHosts(['elasticsearch:9200'])->build(),
        //         'options' => [
        //             'index' => 'laravel-logs',
        //             'type'  => '_doc',
        //         ],
        //     ],
        //     'formatter' => Monolog\Formatter\ElasticsearchFormatter::class,
        //     'formatter_with' => [
        //         'index' => 'laravel-logs',
        //         'type'  => '_doc',
        //     ],
        // ],

        'emergency' => [
            'driver' => 'single', // Указываем драйвер
            'path' => storage_path('logs/emergency.log'),
            'level' => 'error', // Уровень логирования, можно изменить по потребности
        ],

        'file' => [
            'driver' => 'single', // Также указываем драйвер для канала 'file'
            'path' => storage_path('logs/logs.log'),
            'level' => 'debug', // Уровень логирования
        ],
    ],

];
