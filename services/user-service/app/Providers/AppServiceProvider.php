<?php

namespace App\Providers;

use App\Kafka\KafkaConsumer;
use App\Kafka\KafkaProducer;
use App\Prometheus\PrometheusServiceProxy;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Enqueue\RdKafka\RdKafkaConnectionFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Client::class, function () {
            return ClientBuilder::create()
                ->setHosts([config('services.elasticsearch.host')])
                ->build();
        });

        $this->app->singleton(KafkaProducer::class, function ($app) {
            $connectionFactory = new RdKafkaConnectionFactory([
                'global' => [
                    'metadata.broker.list' => config('kafka.broker_list'),
                ],
            ]);

            return new KafkaProducer($connectionFactory);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        DB::listen(function ($query) {
            /** @var PrometheusServiceProxy */
            $prometheusService = app(PrometheusServiceProxy::class);
            $source = optional(request()->route())->getActionName() ?? 'unknown';
            $executionTimeInSeconds = floatval($query->time) / 1000;

            $prometheusService->incrementDatabaseQueryCount($source);
            $prometheusService->addDatabaseQueryTimeHistogram($executionTimeInSeconds, $source);
        });

        // На тестах ломается, так что сделал костыль
        if (!defined('KEY_WITH_RELATIONS')) {
            $this->defineCacheKeysConstants();
        }
    }

    private function defineCacheKeysConstants(): void
    {
        // 
    }
}
