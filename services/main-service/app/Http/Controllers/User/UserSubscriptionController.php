<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\User\UserSubscriptionService;
use Illuminate\Http\Request;

class UserSubscriptionController extends Controller
{
    private $userSubscriptionService;

    public function __construct(UserSubscriptionService $userSubscriptionService)
    {
        $this->userSubscriptionService = $userSubscriptionService;
    }

    public function subscribers(User $user)
    {
        return $this->handleServiceCall(function () use ($user) {
            return $this->userSubscriptionService->subscribers($user);
        });
    }

    public function subscriptions(User $user)
    {
        return $this->handleServiceCall(function () use ($user) {
            return $this->userSubscriptionService->subscriptions($user);
        });
    }
    public function subscribe(User $user)
    {
        return $this->handleServiceCall(function () use ($user) {
            return $this->userSubscriptionService->subscribe($user);
        });
    }
    public function unsubscribe(User $user)
    {
        return $this->handleServiceCall(function () use ($user) {
            return $this->userSubscriptionService->unsubscribe($user);
        });
    }
}
