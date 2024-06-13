<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Client;
use App\Observers\PostLikeObserver;
use App\Models\PostLike;

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
    }
}
