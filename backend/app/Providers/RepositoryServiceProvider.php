<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use App\Repositories\User\UserRepository;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Repositories\Team\TeamVacancyRepository;
use App\Repositories\Team\TeamRepository;
use App\Repositories\Team\TeamMemberRepository;
use App\Repositories\Team\TeamLinkRepository;
use App\Repositories\Team\TeamApplicationRepository;
use App\Repositories\Team\Interfaces\TeamVacancyRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamMemberRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamLinkRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamApplicationRepositoryInterface;
use App\Repositories\TagRepository;
use App\Repositories\SubscriptionRepository;
use App\Repositories\Post\PostRepository;
use App\Repositories\Post\PostLikeRepository;
use App\Repositories\Post\PostCommentRepository;
use App\Repositories\Post\PostCommentLikeRepository;
use App\Repositories\Post\Interfaces\PostRepositoryInterface;
use App\Repositories\Post\Interfaces\PostLikeRepositoryInterface;
use App\Repositories\Post\Interfaces\PostCommentRepositoryInterface;
use App\Repositories\Post\Interfaces\PostCommentLikeRepositoryInterface;
use App\Repositories\NotificationRepository;
use App\Repositories\Message\MessageRepository;
use App\Repositories\Message\Interfaces\MessageRepositoryInterface;
use App\Repositories\Message\Interfaces\ChatRepositoryInterface;
use App\Repositories\Message\Interfaces\ChatMemberRepositoryInterface;
use App\Repositories\Message\ChatRepository;
use App\Repositories\Message\ChatMemberRepository;
use App\Repositories\Interfaces\TagRepositoryInterface;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use App\Repositories\Interfaces\NotificationRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // User
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        // Team
        $this->app->bind(TeamApplicationRepositoryInterface::class, TeamApplicationRepository::class);
        $this->app->bind(TeamLinkRepositoryInterface::class, TeamLinkRepository::class);
        $this->app->bind(TeamMemberRepositoryInterface::class, TeamMemberRepository::class);
        $this->app->bind(TeamRepositoryInterface::class, TeamRepository::class);
        $this->app->bind(TeamVacancyRepositoryInterface::class, TeamVacancyRepository::class);

        // Post
        $this->app->bind(PostCommentLikeRepositoryInterface::class, PostCommentLikeRepository::class);
        $this->app->bind(PostCommentRepositoryInterface::class, PostCommentRepository::class);
        $this->app->bind(PostLikeRepositoryInterface::class, PostLikeRepository::class);
        $this->app->bind(PostRepositoryInterface::class, PostRepository::class);

        // Message
        $this->app->bind(ChatMemberRepositoryInterface::class, ChatMemberRepository::class);
        $this->app->bind(ChatRepositoryInterface::class, ChatRepository::class);
        $this->app->bind(MessageRepositoryInterface::class, MessageRepository::class);

        // Tag
        $this->app->bind(TagRepositoryInterface::class, TagRepository::class);

        // Subscription
        $this->app->bind(SubscriptionRepositoryInterface::class, SubscriptionRepository::class);

        // Notification
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
