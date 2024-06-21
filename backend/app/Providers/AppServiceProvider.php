<?php

namespace App\Providers;

use Monolog\Logger;
use Monolog\Level;
use Monolog\Handler\StreamHandler;
use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client as GuzzleClient;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Client;
use App\Prometheus\PrometheusServiceProxy;
use App\Prometheus\PrometheusService;
use App\Prometheus\IPrometheusService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Client::class, function () {
            $logger = new Logger('name');
            $logger->pushHandler(new StreamHandler('storage/logs/elasticsearch.log', Level::Debug));

            $guzzleClient = new GuzzleClient([
                'timeout' => 10,        // Таймаут на соединение в секундах
                'connect_timeout' => 5  // Таймаут на подключение в секундах
            ]);

            return ClientBuilder::create()
                ->setHosts([config('services.elasticsearch.host')])
                ->setHttpClient($guzzleClient)
                ->setLogger($logger)
                ->build();
        });

        $this->app->singleton(PrometheusService::class, function ($app) {
            return new PrometheusService();
        });

        // Регистрация PrometheusServiceProxy
        $this->app->singleton(IPrometheusService::class, function ($app) {
            return new PrometheusServiceProxy($app->make(PrometheusService::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 
    }
}
