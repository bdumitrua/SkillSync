<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Enqueue\RdKafka\RdKafkaConnectionFactory;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Client;
use App\Services\User\UserSubscriptionService;
use App\Services\User\UserService;
use App\Services\User\UserInterestService;
use App\Services\User\Interfaces\UserSubscriptionServiceInterface;
use App\Services\User\Interfaces\UserServiceInterface;
use App\Services\User\Interfaces\UserInterestServiceInterface;
use App\Services\Team\TeamService;
use App\Services\Team\Interfaces\TeamServiceInterface;
use App\Services\Post\PostService;
use App\Services\Post\Interfaces\PostServiceInterface;
use App\Repositories\User\Interfaces\UserSubscriptionRepositoryInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Repositories\User\Interfaces\UserInterestRepositoryInterface;
use App\Repositories\Team\TeamVacancyRepository;
use App\Repositories\Team\TeamScopeRepository;
use App\Repositories\Team\TeamRepository;
use App\Repositories\Team\TeamMemberRepository;
use App\Repositories\Team\TeamLinkRepository;
use App\Repositories\Team\TeamApplicationRepository;
use App\Repositories\Team\Interfaces\TeamVacancyRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamScopeRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamMemberRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamLinkRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamApplicationRepositoryInterface;
use App\Prometheus\PrometheusServiceProxy;
use App\Kafka\KafkaProducer;
use App\Kafka\KafkaConsumer;
use App\Repositories\Message\ChatMemberRepository;
use App\Repositories\Message\ChatRepository;
use App\Repositories\Message\Interfaces\ChatMemberRepositoryInterface;
use App\Repositories\Message\Interfaces\ChatRepositoryInterface;
use App\Repositories\Message\Interfaces\MessageRepositoryInterface;
use App\Repositories\Message\MessageRepository;
use App\Repositories\Post\Interfaces\PostCommentLikeRepositoryInterface;
use App\Repositories\Post\Interfaces\PostCommentRepositoryInterface;
use App\Repositories\Post\Interfaces\PostLikeRepositoryInterface;
use App\Repositories\Post\Interfaces\PostRepositoryInterface;
use App\Repositories\Post\PostCommentLikeRepository;
use App\Repositories\Post\PostCommentRepository;
use App\Repositories\Post\PostLikeRepository;
use App\Repositories\Post\PostRepository;
use App\Repositories\User\UserInterestRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserSubscriptionRepository;
use App\Services\Message\ChatMemberService;
use App\Services\Message\ChatService;
use App\Services\Message\Interfaces\ChatMemberServiceInterface;
use App\Services\Message\Interfaces\ChatServiceInterface;
use App\Services\Message\Interfaces\MessageServiceInterface;
use App\Services\Message\MessageService;
use App\Services\Post\Interfaces\PostCommentLikeServiceInterface;
use App\Services\Post\Interfaces\PostCommentServiceInterface;
use App\Services\Post\Interfaces\PostLikeServiceInterface;
use App\Services\Post\PostCommentLikeService;
use App\Services\Post\PostCommentService;
use App\Services\Post\PostLikeService;
use App\Services\Team\Interfaces\TeamApplicationServiceInterface;
use App\Services\Team\Interfaces\TeamLinkServiceInterface;
use App\Services\Team\Interfaces\TeamMemberServiceInterface;
use App\Services\Team\Interfaces\TeamScopeServiceInterface;
use App\Services\Team\Interfaces\TeamVacancyServiceInterface;
use App\Services\Team\TeamApplicationService;
use App\Services\Team\TeamLinkService;
use App\Services\Team\TeamMemberService;
use App\Services\Team\TeamScopeService;
use App\Services\Team\TeamVacancyService;

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

        /*
        *   Services
        */

        // User
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(UserInterestServiceInterface::class, UserInterestService::class);
        $this->app->bind(UserSubscriptionServiceInterface::class, UserSubscriptionService::class);

        // Team
        $this->app->bind(TeamApplicationServiceInterface::class, TeamApplicationService::class);
        $this->app->bind(TeamLinkServiceInterface::class, TeamLinkService::class);
        $this->app->bind(TeamMemberServiceInterface::class, TeamMemberService::class);
        $this->app->bind(TeamScopeServiceInterface::class, TeamScopeService::class);
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

        /*
        *   Repositories 
        */

        // User
        $this->app->bind(UserInterestRepositoryInterface::class, UserInterestRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserSubscriptionRepositoryInterface::class, UserSubscriptionRepository::class);

        // Team
        $this->app->bind(TeamApplicationRepositoryInterface::class, TeamApplicationRepository::class);
        $this->app->bind(TeamLinkRepositoryInterface::class, TeamLinkRepository::class);
        $this->app->bind(TeamMemberRepositoryInterface::class, TeamMemberRepository::class);
        $this->app->bind(TeamRepositoryInterface::class, TeamRepository::class);
        $this->app->bind(TeamScopeRepositoryInterface::class, TeamScopeRepository::class);
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
