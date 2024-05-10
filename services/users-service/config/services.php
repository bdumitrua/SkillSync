<?php

return [
    'teams_service' => [
        'url' => env('TEAMS_SERVICE_URL', 'http://teams-service:80/api')
    ],
    'posts_service' => [
        'url' => env('POSTS_SERVICE_URL', 'http://posts-service:8000/')
    ],
    'messages_service' => [
        'url' => env('MESSAGES_SERVICE_URL', 'http://messages-service:3000/')
    ],
    'elasticsearch' => [
        'host' => env('ELASTICSEARCH_HOST'),
    ],
];
