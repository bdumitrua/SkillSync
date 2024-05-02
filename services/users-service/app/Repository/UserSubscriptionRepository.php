<?php

namespace App\Repository;

use App\Helpers\ResponseHelper;
use App\Models\UserSubscription;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

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
     * @return Builder
     */
    protected function queryByBothIds(int $subscriberId, int $subscribedId): Builder
    {
        return $this->userSubscription->newQuery()->where([
            'subscriber_id' => $subscriberId,
            'subscribed_id' => $subscribedId,
        ]);
    }

    /**
     * @param int $subscriberId
     * @param int $subscribedId
     * 
     * @return bool
     */
    public function existsByBothIds(int $subscriberId, int $subscribedId): bool
    {
        return $this->queryByBothIds($subscriberId, $subscribedId)->exists();
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
     * @return Response
     */
    public function subscribe(int $subscriberId, int $subscribedId): Response
    {
        $subscriptionExists = $this->queryByBothIds($subscriberId, $subscribedId)->exists();
        if ($subscriptionExists) {
            return ResponseHelper::noContent();
        }

        $this->userSubscription->create([
            'subscriber_id' => $subscriberId,
            'subscribed_id' => $subscribedId,
        ]);

        return ResponseHelper::successResponse();
    }

    /**
     * @param int $subscriberId
     * @param int $subscribedId
     * 
     * @return Response
     */
    public function unsubscribe(int $subscriberId, int $subscribedId): Response
    {
        $subscription = $this->queryByBothIds($subscriberId, $subscribedId)->first();
        if (empty($subscription)) {
            return ResponseHelper::badRequest();
        }

        $subscription->delete();

        return ResponseHelper::successResponse();
    }
}
