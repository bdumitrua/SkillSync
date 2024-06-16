<?php

namespace App\Providers;

use Monolog\Logger;
use Monolog\Level;
use Monolog\Handler\StreamHandler;
use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client as GuzzleClient;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Client;
use App\Observers\SubscriptionObserver;
use App\Observers\NotificationObserver;
use App\Observers\LikeObserver;
use App\Observers\ElasticsearchObserver;
use App\Models\User;
use App\Models\Team;
use App\Models\Subscription;
use App\Models\Project;
use App\Models\Post;
use App\Models\Notification;
use App\Models\Like;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        /**
         * Register any application services.
         */
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Like::observe(LikeObserver::class);
        Subscription::observe(SubscriptionObserver::class);
        Notification::observe(NotificationObserver::class);

        // Searchable
        User::observe(ElasticsearchObserver::class);
        Team::observe(ElasticsearchObserver::class);
        Post::observe(ElasticsearchObserver::class);
        Project::observe(ElasticsearchObserver::class);
    }
}
