<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Enqueue\RdKafka\RdKafkaConnectionFactory;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Client;
use App\Observers\PostLikeObserver;
use App\Models\PostLike;
use App\Kafka\KafkaProducer;
use App\Kafka\KafkaConsumer;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        /**
         * Register any application services.
         */
        $this->app->singleton(Client::class, function () {
            return ClientBuilder::create()
                ->setHosts([config('services.elasticsearch.host')])
                ->setRetries(3)
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
        PostLike::observe(PostLikeObserver::class);
    }
}
