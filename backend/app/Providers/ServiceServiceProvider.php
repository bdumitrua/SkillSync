<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\User\UserService;
use App\Services\User\Interfaces\UserServiceInterface;
use App\Services\Team\TeamVacancyService;
use App\Services\Team\TeamService;
use App\Services\Team\TeamMemberService;
use App\Services\Team\TeamLinkService;
use App\Services\Team\TeamApplicationService;
use App\Services\Team\Interfaces\TeamVacancyServiceInterface;
use App\Services\Team\Interfaces\TeamServiceInterface;
use App\Services\Team\Interfaces\TeamMemberServiceInterface;
use App\Services\Team\Interfaces\TeamLinkServiceInterface;
use App\Services\Team\Interfaces\TeamApplicationServiceInterface;
use App\Services\TagService;
use App\Services\SubscriptionService;
use App\Services\Post\PostService;
use App\Services\Post\PostCommentService;
use App\Services\Post\Interfaces\PostServiceInterface;
use App\Services\Post\Interfaces\PostCommentServiceInterface;
use App\Services\Message\MessageService;
use App\Services\Message\Interfaces\MessageServiceInterface;
use App\Services\Message\Interfaces\ChatServiceInterface;
use App\Services\Message\Interfaces\ChatMemberServiceInterface;
use App\Services\Message\ChatService;
use App\Services\Message\ChatMemberService;
use App\Services\LikeService;
use App\Services\Interfaces\TagServiceInterface;
use App\Services\Interfaces\SubscriptionServiceInterface;
use App\Services\Interfaces\LikeServiceInterface;

class ServiceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // User
        $this->app->bind(UserServiceInterface::class, UserService::class);

        // Team
        $this->app->bind(TeamApplicationServiceInterface::class, TeamApplicationService::class);
        $this->app->bind(TeamLinkServiceInterface::class, TeamLinkService::class);
        $this->app->bind(TeamMemberServiceInterface::class, TeamMemberService::class);
        $this->app->bind(TeamServiceInterface::class, TeamService::class);
        $this->app->bind(TeamVacancyServiceInterface::class, TeamVacancyService::class);

        // Post
        $this->app->bind(PostCommentServiceInterface::class, PostCommentService::class);
        $this->app->bind(PostServiceInterface::class, PostService::class);

        // Message
        $this->app->bind(ChatMemberServiceInterface::class, ChatMemberService::class);
        $this->app->bind(ChatServiceInterface::class, ChatService::class);
        $this->app->bind(MessageServiceInterface::class, MessageService::class);

        // Tag
        $this->app->bind(TagServiceInterface::class, TagService::class);

        // Subscription
        $this->app->bind(SubscriptionServiceInterface::class, SubscriptionService::class);

        // Like
        $this->app->bind(LikeServiceInterface::class, LikeService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
