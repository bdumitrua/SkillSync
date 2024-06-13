<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Client;
use App\Observers\SubscriptionObserver;
use App\Observers\PostLikeObserver;
use App\Observers\PostCommentLikeObserver;
use App\Observers\NotificationObserver;
use App\Models\Subscription;
use App\Models\PostLike;
use App\Models\PostCommentLike;
use App\Models\Notification;

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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        PostLike::observe(PostLikeObserver::class);
        PostCommentLike::observe(PostCommentLikeObserver::class);
        Subscription::observe(SubscriptionObserver::class);
        Notification::observe(NotificationObserver::class);
    }
}
