<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
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
use App\Models\GroupChat;
use App\Models\DialogChat;

class ObserverServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // 
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
        DialogChat::observe(ElasticsearchObserver::class);
        GroupChat::observe(ElasticsearchObserver::class);
    }
}
