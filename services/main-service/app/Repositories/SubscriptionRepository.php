<?php

namespace App\Repositories;

use App\DTO\User\CreateSubscriptionDTO;
use App\Models\Subscription;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    protected function querySubscriptionToUser(int $subscriberId, int $targetUserId): Builder
    {
        return Subscription::query()
            ->where('user_id', '=', $subscriberId)
            ->where('entity_type', '=', config('entities.user'))
            ->where('entity_id', '=', $targetUserId);
    }

    protected function querySubscriptionToTeam(int $userId, int $teamId): Builder
    {
        return Subscription::query()
            ->where('user_id', '=', $userId)
            ->where('entity_type', '=', config('entities.team'))
            ->where('entity_id', '=', $teamId);
    }

    public function getUserSubscriptions(int $userId): Collection
    {
        return Subscription::where('user_id', '=', $userId)
            ->groupBy('entity_type')
            ->get();
    }

    public function getUserSubscribers(int $userId): Collection
    {
        return Subscription::where('entity_type', '=', config('entities.user'))
            ->where('entity_id', '=', $userId)
            ->get();
    }

    public function getTeamSubscribers(int $teamId): Collection
    {
        return Subscription::where('entity_type', '=', config('entities.team'))
            ->where('entity_id', '=', $teamId)
            ->get();
    }

    public function getUsersByUserId(int $userId): Collection
    {
        return Subscription::where('user_id', '=', $userId)
            ->where('entity_type', '=', config('entities.user'))
            ->get();
    }

    public function getTeamsByUserId(int $userId): Collection
    {
        return Subscription::where('user_id', '=', $userId)
            ->where('entity_type', '=', config('entities.team'))
            ->get();
    }

    public function subscriptionToUser(int $subscriberId, int $targetUserId): ?Subscription
    {
        return $this->querySubscriptionToUser($subscriberId, $targetUserId)->first();
    }

    public function subscriptionToTeam(int $userId, int $teamId): ?Subscription
    {
        return $this->querySubscriptionToTeam($userId, $teamId)->first();
    }

    public function isSubscribedToUser(int $subscriberId, int $targetUserId): bool
    {
        return $this->querySubscriptionToUser($subscriberId, $targetUserId)->exists();
    }

    public function isSubscribedToTeam(int $userId, int $teamId): bool
    {
        return $this->querySubscriptionToTeam($userId, $teamId)->exists();
    }


    public function create(CreateSubscriptionDTO $dto): bool
    {
        $isSubscribed = false;

        if ($dto->entityType == config('entities.user')) {
            $isSubscribed = $this->isSubscribedToUser($dto->userId, $dto->entityId);
        } else {
            $isSubscribed = $this->isSubscribedToTeam($dto->userId, $dto->entityId);
        }

        if ($isSubscribed) {
            return false;
        }

        Subscription::create(
            $dto->toArray()
        );

        return true;
    }

    public function delete(Subscription $subscription): void
    {
        $subscription->delete();
    }
}
