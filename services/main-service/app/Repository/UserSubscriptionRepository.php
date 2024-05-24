<?php

namespace App\Repository;

use App\Models\UserSubscription;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class UserSubscriptionRepository
{
    private UserSubscription $userSubscription;

    public function __construct(
        UserSubscription $userSubscription,
    ) {
        $this->userSubscription = $userSubscription;
    }

    /**
     * @param int $subscriberId
     * @param int $subscribedId
     * 
     * @return UserSubscription|null
     */
    public function getByBothIds(int $subscriberId, int $subscribedId): ?UserSubscription
    {
        return $this->userSubscription
            ->where([
                'subscriber_id' => $subscriberId,
                'subscribed_id' => $subscribedId,
            ])
            ->first();
    }

    /**
     * @param int $userId
     * 
     * @return array
     */
    public function subscribers(int $userId): array
    {
        return $this->userSubscription
            ->where('subscribed_id', '=', $userId)
            ->get()
            ->map(function ($subscription) {
                return $subscription['subscriber_id'];
            })
            ->toArray();
    }

    /**
     * @param int $userId
     * 
     * @return array
     */
    public function subscriptions(int $userId): array
    {
        return $this->userSubscription
            ->where('subscriber_id', '=', $userId)
            ->get()
            ->map(function ($subscription) {
                return $subscription['subscribed_id'];
            })
            ->toArray();
    }

    /**
     * @param int $subscriberId
     * @param int $subscribedId
     * 
     * @return void
     */
    public function subscribe(int $subscriberId, int $subscribedId): void
    {
        $this->userSubscription->create([
            'subscriber_id' => $subscriberId,
            'subscribed_id' => $subscribedId,
        ]);
    }

    /**
     * @param UserSubscription $userSubscription
     * 
     * @return void
     */
    public function unsubscribe(UserSubscription $userSubscription): void
    {
        $userSubscription->delete();
    }
}
