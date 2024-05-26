<?php

namespace App\Policies;

use App\Models\User;
use App\Repositories\User\Interfaces\UserSubscriptionRepositoryInterface;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    protected $userSubscriptionRepository;

    public function __construct(
        UserSubscriptionRepositoryInterface $userSubscriptionRepository,
    ) {
        $this->userSubscriptionRepository = $userSubscriptionRepository;
    }

    /**
     * Determine whether the user can subscribe to other user.
     * 
     * @see SUBSCRIBE_ON_USER_GATE
     */
    public function subscribeOnUser(User $user, int $secondUserId): Response
    {
        if ($user->id === $secondUserId) {
            return Response::denyWithStatus(400, "You can't subscribe to yourself");
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can unsubscribe from other user.
     * 
     * @see UNSUBSCRIBE_FROM_USER_GATE
     */
    public function unsubscribeFromUser(User $user, int $secondUserId): Response
    {
        if ($user->id === $secondUserId) {
            return Response::denyWithStatus(400, "You can't unsubscribe from yourself");
        }

        return Response::allow();
    }
}
