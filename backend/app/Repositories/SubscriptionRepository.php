<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use App\Models\Subscription;
use App\DTO\User\CreateSubscriptionDTO;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    protected function querySubscriptionToUser(int $subscriberId, int $targetUserId): Builder
    {
        return Subscription::query()
            ->where('subscriber_id', '=', $subscriberId)
            ->where('entity_type', '=', config('entities.user'))
            ->where('entity_id', '=', $targetUserId);
    }

    protected function querySubscriptionToTeam(int $userId, int $teamId): Builder
    {
        return Subscription::query()
            ->where('subscriber_id', '=', $userId)
            ->where('entity_type', '=', config('entities.team'))
            ->where('entity_id', '=', $teamId);
    }

    public function getUserSubscriptions(int $userId): Collection
    {
        Log::debug('Getting user subscriptions', [
            'userId' => $userId
        ]);

        return Subscription::where('subscriber_id', '=', $userId)
            ->groupBy('entity_type')
            ->get();
    }

    public function getUserSubscribers(int $userId): Collection
    {
        Log::debug('Getting user subscribers', [
            'userId' => $userId
        ]);

        return Subscription::where('entity_type', '=', config('entities.user'))
            ->where('entity_id', '=', $userId)
            ->get();
    }

    public function getTeamSubscribers(int $teamId): Collection
    {
        Log::debug('Getting team subscribers', [
            'teamId' => $teamId
        ]);

        return Subscription::where('entity_type', '=', config('entities.team'))
            ->where('entity_id', '=', $teamId)
            ->get();
    }

    public function getUsersByUserId(int $userId): Collection
    {
        Log::debug('Getting user subscribtions to other users', [
            'userId' => $userId
        ]);

        return Subscription::where('subscriber_id', '=', $userId)
            ->where('entity_type', '=', config('entities.user'))
            ->get();
    }

    public function getTeamsByUserId(int $userId): Collection
    {
        Log::debug('Getting user subscribtions to teams', [
            'userId' => $userId
        ]);

        return Subscription::where('subscriber_id', '=', $userId)
            ->where('entity_type', '=', config('entities.team'))
            ->get();
    }

    public function isSubscribedToUser(int $subscriberId, int $targetUserId): bool
    {
        Log::debug('Checking if user is subscribed to other user', [
            'subscriberId' => $subscriberId,
            'targetUserId' => $targetUserId
        ]);

        return $this->querySubscriptionToUser($subscriberId, $targetUserId)->exists();
    }

    public function isSubscribedToTeam(int $userId, int $teamId): bool
    {
        Log::debug('Checking if user is subscribed to team', [
            'userId' => $userId,
            'teamId' => $teamId
        ]);

        return $this->querySubscriptionToTeam($userId, $teamId)->exists();
    }


    public function create(CreateSubscriptionDTO $dto): bool
    {
        Log::debug('Start creating new subscription', [
            'dto' => $dto->toArray()
        ]);

        $isSubscribed = false;

        if ($dto->entityType == config('entities.user')) {
            $isSubscribed = $this->isSubscribedToUser($dto->userId, $dto->entityId);
        } else {
            $isSubscribed = $this->isSubscribedToTeam($dto->userId, $dto->entityId);
        }

        if ($isSubscribed) {
            return false;
        }

        Log::debug('Creating new subscription', [
            'dto' => $dto->toArray()
        ]);

        $newSubscription = Subscription::create(
            $dto->toArray()
        );

        Log::debug('New subscription created succesfully', [
            'newSubscription' => $newSubscription->toArray()
        ]);

        return true;
    }

    public function delete(Subscription $subscription): void
    {
        $subscriptionData = $subscription->toArray();

        Log::debug('Deleting subscription', [
            'subscription' => $subscriptionData
        ]);

        $subscription->delete();

        Log::debug('Subscription deleted succesfully', [
            'subscription' => $subscriptionData
        ]);
    }
}
