<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Services\Interfaces\SubscriptionServiceInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use App\Models\User;
use App\Models\Team;
use App\Models\Subscription;
use App\Exceptions\NotFoundException;
use App\DTO\SubscriptionDTO;

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

        $userSubscriptions = $this->subscriptionRepository->getUsersByUserId($userId);
        $this->fillSubscriptionsUserData($userSubscriptions);

        // TODO CREATE RESOURCE
        return JsonResource::collection($userSubscriptions);
    }

    public function teams(int $userId): JsonResource
    {
        Log::debug('Started getting user subscriptions to teams', [
            'userId' => $userId
        ]);

        $teamSubscriptions = $this->subscriptionRepository->getTeamsByUserId($userId);
        $this->fillSubscriptionsTeamData($teamSubscriptions);

        return JsonResource::collection($teamSubscriptions);
    }

    public function user(User $user): JsonResource
    {
        Log::debug('Started getting subscriptions to user', [
            'userId' => $user->id
        ]);

        $userSubscriptions = $this->subscriptionRepository->getUserSubscribers($user->id);
        $this->fillSubscriptionsUserData($userSubscriptions);

        return JsonResource::collection($userSubscriptions);
    }

    public function team(Team $team): JsonResource
    {
        Log::debug('Started getting subscriptions to team', [
            'teamId' => $team->id
        ]);

        $userSubscriptions = $this->subscriptionRepository->getTeamSubscribers($team->id);
        $this->fillSubscriptionsUserData($userSubscriptions);

        return JsonResource::collection($userSubscriptions);
    }

    public function create(SubscriptionDTO $subscriptionDTO): void
    {
        $entity = $this->getEntityByDTO($subscriptionDTO);
        if (empty($entity)) {
            throw new NotFoundException('Model to subscribe');
        }

        Gate::authorize(
            'create',
            [
                Subscription::class,
                $subscriptionDTO->entityType,
                $subscriptionDTO->entityId
            ]
        );

        $this->subscriptionRepository->create($subscriptionDTO);
    }

    public function delete(SubscriptionDTO $subscriptionDTO): void
    {
        $subscription = $this->subscriptionRepository->getByDTO($subscriptionDTO);
        if (empty($subscription)) {
            throw new NotFoundException('Subscription');
        }

        Gate::authorize('delete', [Subscription::class, $subscription]);

        $this->subscriptionRepository->delete($subscription);
    }

    protected function getEntityByDTO(SubscriptionDTO $subscriptionDTO): ?Model
    {
        if ($subscriptionDTO->entityType === config('entities.user')) {
            return $this->userRepository->getById($subscriptionDTO->entityId);
        } elseif ($subscriptionDTO->entityType === config('entities.team')) {
            return $this->teamRepository->getById($subscriptionDTO->entityId);
        }

        Log::warning("entityType from SubscriptionDTO didn't match any type of repository", [
            'SubscriptionDTO' => $subscriptionDTO->toArray()
        ]);
        return null;
    }

    protected function fillSubscriptionsUserData(Collection &$userSubscriptions): void
    {
        $userIds = $userSubscriptions->pluck('entity_id')->toArray();
        $usersData = $this->userRepository->getByIds($userIds);

        foreach ($userSubscriptions as &$subscription) {
            $subscription->userData = $usersData->where('id', '=', $subscription->entity_id);
        }
    }

    protected function fillSubscriptionsTeamData(Collection &$teamSubscriptions): void
    {
        $teamIds = $teamSubscriptions->pluck('entity_id')->toArray();
        $teamsData = $this->teamRepository->getByIds($teamIds);

        foreach ($teamSubscriptions as &$subscription) {
            $subscription->teamData = $teamsData->where('id', '=', $subscription->entity_id);
        }
    }
}
