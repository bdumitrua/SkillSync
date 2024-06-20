<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\UserObserver;
use App\Observers\TeamVacancyObserver;
use App\Observers\TeamObserver;
use App\Observers\TeamMemberObserver;
use App\Observers\TagObserver;
use App\Observers\SubscriptionObserver;
use App\Observers\ProjectObserver;
use App\Observers\PostObserver;
use App\Observers\NotificationObserver;
use App\Observers\LikeObserver;
use App\Observers\ElasticsearchObserver;
use App\Models\User;
use App\Models\TeamVacancy;
use App\Models\TeamMember;
use App\Models\Team;
use App\Models\Tag;
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
        User::observe(UserObserver::class);
        Team::observe(TeamObserver::class);
        TeamMember::observe(TeamMemberObserver::class);
        TeamVacancy::observe(TeamVacancyObserver::class);
        Post::observe(PostObserver::class);
        Project::observe(ProjectObserver::class);
        Tag::observe(TagObserver::class);
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
