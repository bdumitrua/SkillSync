<?php

namespace App\Services;

use App\DTO\CreateSubscriptionDTO;
use App\Exceptions\SubscriptionException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\Interfaces\SubscriptionServiceInterface;
use App\Models\Subscription;
use App\Http\Requests\CreateSubscriptionRequest;
use App\Http\Resources\Team\TeamDataResource;
use App\Http\Resources\User\UserDataResource;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Traits\CreateDTO;
use Illuminate\Support\Facades\Log;

class SubscriptionService implements SubscriptionServiceInterface
{
    protected $teamRepository;
    protected $userRepository;
    protected $subscriptionRepository;
    protected ?int $authorizedUserId;

    public function __construct(
        TeamRepositoryInterface $teamRepository,
        UserRepositoryInterface $userRepository,
        SubscriptionRepositoryInterface $subscriptionRepository,
    ) {
        $this->teamRepository = $teamRepository;
        $this->userRepository = $userRepository;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->authorizedUserId = Auth::id();
    }

    public function users(int $userId): JsonResource
    {
        Log::debug('Started getting user subscriptions to other users', [
            'userId' => $userId
        ]);

        $userIds = $this->subscriptionRepository->getUsersByUserId($userId)
            ->pluck('entity_id')->toArray();
        $usersData = $this->userRepository->getByIds($userIds);

        return UserDataResource::collection($usersData);
    }

    public function teams(int $userId): JsonResource
    {
        Log::debug('Started getting user subscriptions to teams', [
            'userId' => $userId
        ]);

        $teamIds = $this->subscriptionRepository->getTeamsByUserId($userId)
            ->pluck('entity_id')->toArray();
        $teamsData = $this->teamRepository->getByIds($teamIds);

        return TeamDataResource::collection($teamsData);
    }

    public function create(CreateSubscriptionDTO $createSubscriptionDTO): void
    {
        Gate::authorize(
            SUBSCRIBE_TO_ENTITY_GATE,
            [
                Subscription::class,
                $createSubscriptionDTO->entityType,
                $createSubscriptionDTO->entityId
            ]
        );

        $subscribed = $this->subscriptionRepository->create($createSubscriptionDTO);
        if (!$subscribed) {
            throw new SubscriptionException("You're already subscribed.");
        }
    }

    public function delete(Subscription $subscription): void
    {
        Gate::authorize(
            UNSUBSCRIBE_FROM_ENTITY_GATE,
            [Subscription::class, $subscription->entity_type, $subscription->entity_id]
        );

        $this->subscriptionRepository->delete($subscription);
    }
}
