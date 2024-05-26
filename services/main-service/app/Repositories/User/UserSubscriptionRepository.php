<?php

namespace App\Repositories\User;

use App\Models\UserSubscription;
use App\Repositories\User\Interfaces\UserSubscriptionRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class UserSubscriptionRepository implements UserSubscriptionRepositoryInterface
{
    public function queryByBothIds(int $subscriberId, int $subscribedId): Builder
    {
        return UserSubscription::query()
            ->where('subscriber_id', '=', $subscriberId)
            ->where('subscribed_id', '= ', $subscribedId);
    }

    public function getByBothIds(int $subscriberId, int $subscribedId): ?UserSubscription
    {
        return $this->queryByBothIds($subscriberId, $subscribedId)->first();
    }

    function userIsSubscribedToUser(int $subscriberId, int $subscribedId): bool
    {
        return $this->queryByBothIds($subscriberId, $subscribedId)->exists();
    }

    public function subscribers(int $userId): array
    {
        return UserSubscription::where('subscribed_id', '=', $userId)
            ->get()
            ->map(function ($subscription) {
                return $subscription['subscriber_id'];
            })
            ->toArray();
    }

    public function subscriptions(int $userId): array
    {
        return UserSubscription::where('subscriber_id', '=', $userId)
            ->get()
            ->map(function ($subscription) {
                return $subscription['subscribed_id'];
            })
            ->toArray();
    }

    public function subscribe(int $subscriberId, int $subscribedId): bool
    {
        $isSubscribed = $this->userIsSubscribedToUser($subscriberId, $subscribedId);

        if ($isSubscribed) {
            return false;
        }

        UserSubscription::create([
            'subscriber_id' => $subscriberId,
            'subscribed_id' => $subscribedId,
        ]);

        return true;
    }

    function unsubscribe(int $subscriberId, int $subscribedId): bool
    {
        $userSubscription = $this->getByBothIds($subscriberId, $subscribedId);

        if (empty($userSubscription)) {
            return false;
        }

        return $userSubscription->delete();
    }
}
