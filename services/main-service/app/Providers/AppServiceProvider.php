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
use App\Repositories\UserSubscriptionRepository;
use App\Repositories\UserRepository;
use App\Repositories\UserInterestRepository;
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

        // Services

        // Post
        $this->app->bind(PostServiceInterface::class, PostService::class);

        // Team
        $this->app->bind(TeamServiceInterface::class, TeamService::class);

        // user
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(UserInterestServiceInterface::class, UserInterestService::class);
        $this->app->bind(UserSubscriptionServiceInterface::class, UserSubscriptionService::class);

        // Repositories

        // Team
        $this->app->bind(TeamApplicationRepositoryInterface::class, TeamApplicationRepository::class);
        $this->app->bind(TeamLinkRepositoryInterface::class, TeamLinkRepository::class);
        $this->app->bind(TeamMemberRepositoryInterface::class, TeamMemberRepository::class);
        $this->app->bind(TeamRepositoryInterface::class, TeamRepository::class);
        $this->app->bind(TeamScopeRepositoryInterface::class, TeamScopeRepository::class);
        $this->app->bind(TeamVacancyRepositoryInterface::class, TeamVacancyRepository::class);

        // User
        $this->app->bind(UserInterestRepositoryInterface::class, UserInterestRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserSubscriptionRepositoryInterface::class, UserSubscriptionRepository::class);
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
