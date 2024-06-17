<?php

return [
    /*
    * Package Service Providers...
    */
    Tymon\JWTAuth\Providers\LaravelServiceProvider::class,

    /*
    * Application Service Providers...
    */
    App\Providers\AppServiceProvider::class,
    App\Providers\BroadcastServiceProvider::class,
    App\Providers\CacheServiceProvider::class,
    App\Providers\DatabaseServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\GateServiceProvider::class,
    App\Providers\RepositoryServiceProvider::class,
    App\Providers\RouteServiceProvider::class,
    App\Providers\ServiceServiceProvider::class,
    App\Providers\ObserverServiceProvider::class,
];
