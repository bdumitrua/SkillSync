<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use App\Models\Subscription;
use App\DTO\SubscriptionDTO;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    public function getByDTO(SubscriptionDTO $subscriptionDTO): ?Subscription
    {
        return Subscription::where('subscriber_id', '=', $subscriptionDTO->subscriberId)
            ->where('entity_type', '=', $subscriptionDTO->entityType)
            ->where('entity_id', '=', $subscriptionDTO->entityId)
            ->first();
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

    public function getUserSubscriptions(int $userId): Collection
    {
        Log::debug('Getting user subscriptions', [
            'userId' => $userId
        ]);

        return Subscription::where('subscriber_id', '=', $userId)->get();
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

        return Subscription::query()
            ->where('subscriber_id', '=', $subscriberId)
            ->where('entity_type', '=', config('entities.user'))
            ->where('entity_id', '=', $targetUserId)
            ->exists();
    }

    public function isSubscribedToTeam(int $userId, int $teamId): bool
    {
        Log::debug('Checking if user is subscribed to team', [
            'userId' => $userId,
            'teamId' => $teamId
        ]);

        return Subscription::query()
            ->where('subscriber_id', '=', $userId)
            ->where('entity_type', '=', config('entities.team'))
            ->where('entity_id', '=', $teamId)
            ->exists();
    }

    public function create(SubscriptionDTO $dto): void
    {
        Log::debug('Creating new subscription', [
            'dto' => $dto->toArray()
        ]);

        Subscription::create(
            $dto->toArray()
        );
    }

    public function delete(Subscription $subscription): void
    {
        Log::debug('Deleting subscription', [
            'subscription' => $subscription->toArray()
        ]);

        $subscription->delete();
    }
}
