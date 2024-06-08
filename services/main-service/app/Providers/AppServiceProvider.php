<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Enqueue\RdKafka\RdKafkaConnectionFactory;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Client;
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
use App\Services\Post\PostLikeService;
use App\Services\Post\PostCommentService;
use App\Services\Post\PostCommentLikeService;
use App\Services\Post\Interfaces\PostServiceInterface;
use App\Services\Post\Interfaces\PostLikeServiceInterface;
use App\Services\Post\Interfaces\PostCommentServiceInterface;
use App\Services\Post\Interfaces\PostCommentLikeServiceInterface;
use App\Services\Message\MessageService;
use App\Services\Message\Interfaces\MessageServiceInterface;
use App\Services\Message\Interfaces\ChatServiceInterface;
use App\Services\Message\Interfaces\ChatMemberServiceInterface;
use App\Services\Message\ChatService;
use App\Services\Message\ChatMemberService;
use App\Services\Interfaces\TagServiceInterface;
use App\Services\Interfaces\SubscriptionServiceInterface;
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
use App\Repositories\Message\MessageRepository;
use App\Repositories\Message\Interfaces\MessageRepositoryInterface;
use App\Repositories\Message\Interfaces\ChatRepositoryInterface;
use App\Repositories\Message\Interfaces\ChatMemberRepositoryInterface;
use App\Repositories\Message\ChatRepository;
use App\Repositories\Message\ChatMemberRepository;
use App\Repositories\Interfaces\TagRepositoryInterface;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use App\Prometheus\PrometheusServiceProxy;
use App\Kafka\KafkaProducer;
use App\Kafka\KafkaConsumer;
use App\Models\PostLike;
use App\Observers\PostLikeObserver;

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

        /*
        *   Services
        */

        // User
        $this->app->bind(UserServiceInterface::class, UserService::class);

        // Team
        $this->app->bind(TeamApplicationServiceInterface::class, TeamApplicationService::class);
        $this->app->bind(TeamLinkServiceInterface::class, TeamLinkService::class);
        $this->app->bind(TeamMemberServiceInterface::class, TeamMemberService::class);
        $this->app->bind(TeamServiceInterface::class, TeamService::class);
        $this->app->bind(TeamVacancyServiceInterface::class, TeamVacancyService::class);

        // Post
        $this->app->bind(PostCommentLikeServiceInterface::class, PostCommentLikeService::class);
        $this->app->bind(PostCommentServiceInterface::class, PostCommentService::class);
        $this->app->bind(PostLikeServiceInterface::class, PostLikeService::class);
        $this->app->bind(PostServiceInterface::class, PostService::class);

        // Message
        $this->app->bind(ChatMemberServiceInterface::class, ChatMemberService::class);
        $this->app->bind(ChatServiceInterface::class, ChatService::class);
        $this->app->bind(MessageServiceInterface::class, MessageService::class);

        // Tag
        $this->app->bind(TagServiceInterface::class, TagService::class);

        // Subscription
        $this->app->bind(SubscriptionServiceInterface::class, SubscriptionService::class);

        /*
        *   Repositories 
        */

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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        PostLike::observe(PostLikeObserver::class);

        if ($this->app->environment('local')) {
            DB::listen(function ($query) {
                Log::info('Query Time: ' . $query->time . 'ms', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                ]);
            });
        }

        // DB::listen(function ($query) {
        //     /** @var PrometheusServiceProxy */
        //     $prometheusService = app(PrometheusServiceProxy::class);
        //     $source = optional(request()->route())->getActionName() ?? 'unknown';
        //     $executionTimeInSeconds = floatval($query->time) / 1000;

        //     $prometheusService->incrementDatabaseQueryCount($source);
        //     $prometheusService->addDatabaseQueryTimeHistogram($executionTimeInSeconds, $source);
        // });

        if (!defined('CONSTANTS_DEFINED')) {
            $this->defineCacheTimeConstants();
            $this->defineCacheKeysConstants();
            $this->defineGateMethodsConstants();

            define('CONSTANTS_DEFINED', true);
        }
    }

    private function defineCacheTimeConstants(): void
    {
        /*
        *   Cache time
        */

        $hour = 60 * 60;

        define('CACHE_TIME_USER_DATA', 3 * $hour);

        define('CACHE_TIME_TEAM_DATA', 24 * $hour);
        define('CACHE_TIME_TEAM_USER_MODERATOR', 1 * $hour);
        define('CACHE_TIME_TEAM_LINKS_DATA', 24 * $hour);
        define('CACHE_TIME_TEAM_VACANCY_DATA', 1 * $hour);

        define('CACHE_TIME_POST_DATA', 1 * $hour);

        define('CACHE_TIME_TEAM_TAGS_DATA', 24 * $hour);
        define('CACHE_TIME_USER_TAGS_DATA', 3 * $hour);
        define('CACHE_TIME_POST_TAGS_DATA', 1 * $hour);

        define('CACHE_TIME_TEAM_POST_DATA', 3 * $hour);
        define('CACHE_TIME_USER_POST_DATA', 1 * $hour);

        define('CACHE_TIME_TEAM_VACANCIES_DATA', 3 * $hour);
    }

    private function defineCacheKeysConstants(): void
    {
        /*
        *   Cache keys
        */

        define('CACHE_KEY_USER_DATA', 'user_data:');

        define('CACHE_KEY_TEAM_DATA', 'team_data:');
        // :teamId:userId
        define('CACHE_KEY_TEAM_USER_MODERATOR', 'team_user_moderator_data:');
        // :teamId:true/false - isMember
        define('CACHE_KEY_TEAM_LINKS_DATA', 'team_links_data:');
        define('CACHE_KEY_TEAM_VACANCY_DATA', 'team_vacancy_data:');

        define('CACHE_KEY_POST_DATA', 'post_data:');

        define('CACHE_KEY_TEAM_TAGS_DATA', 'team_tags_data:');
        define('CACHE_KEY_USER_TAGS_DATA', 'user_tags_data:');
        define('CACHE_KEY_POST_TAGS_DATA', 'post_tags_data:');

        define('CACHE_KEY_TEAM_POST_DATA', 'team_post_data:');
        define('CACHE_KEY_USER_POST_DATA', 'user_post_data:');

        define('CACHE_KEY_TEAM_VACANCIES_DATA', 'team_vacancies_data:');
    }

    private function defineGateMethodsConstants(): void
    {
        /*
        *   Policies
        */

        // Team
        define('UPDATE_TEAM_GATE', 'updateTeam');
        define('DELETE_TEAM_GATE', 'deleteTeam');
        define('MONITOR_TEAM_APPLICATIONS_GATE', 'monitorTeamApplications');
        define('TOUCH_TEAM_VACANCIES_GATE', 'touchTeamVacancies');
        define('TOUCH_TEAM_POSTS_GATE', 'touchTeamPosts');
        define('TOUCH_TEAM_TAGS_GATE', 'touchTeamTags');
        define('TOUCH_TEAM_LINKS_GATE', 'touchTeamLinks');
        define('TOUCH_TEAM_MEMBERS_GATE', 'touchTeamMembers');

        define('VIEW_TEAM_APPLICATIONS_GATE', 'viewTeamApplication');
        define('APPLY_TO_VACANCY_GATE', 'applyToVacancy');
        define('UPDATE_TEAM_APPLICATION_GATE', 'updateTeamApplication');
        define('DELETE_TEAM_APPLICATION_GATE', 'deleteTeamApplication');

        // Post
        define('CREATE_POST_GATE', 'createPost');
        define('UPDATE_POST_GATE', 'updatePost');
        define('DELETE_POST_GATE', 'deletePost');

        define('DELETE_POST_COMMENT_GATE', 'deletePostComment');

        // TAG
        define('CREATE_TAG_GATE', 'createTag');
        define('DELETE_TAG_GATE', 'deleteTag');

        // Subscription
        define('SUBSCRIBE_TO_ENTITY_GATE', 'subscribeToEntity');
        define('UNSUBSCRIBE_FROM_ENTITY_GATE', 'unsubscribeFromEntity');
    }
}
