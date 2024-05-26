<?php

namespace App\Repositories\User\Interfaces;

use App\Models\UserSubscription;

interface UserSubscriptionRepositoryInterface
{
    /**
     * @param int $subscriberId
     * @param int $subscribedId
     * 
     * @return UserSubscription|null
     */
    function getByBothIds(int $subscriberId, int $subscribedId): ?UserSubscription;

    /**
     * @param int $subscriberId
     * @param int $subscribedId
     * 
     * @return bool
     */
    function userIsSubscribedToUser(int $subscriberId, int $subscribedId): bool;

    /**
     * @param int $userId
     * 
     * @return array
     */
    function subscribers(int $userId): array;

    /**
     * @param int $userId
     * 
     * @return array
     */
    function subscriptions(int $userId): array;

    /**
     * @param int $subscriberId
     * @param int $subscribedId
     * 
     * @return bool
     */
    function subscribe(int $subscriberId, int $subscribedId): bool;

    /**
     * @param int $subscriberId
     * @param int $subscribedId
     * 
     * @return bool
     */
    function unsubscribe(int $subscriberId, int $subscribedId): bool;
}
