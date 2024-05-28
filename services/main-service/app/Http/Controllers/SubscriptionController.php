<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSubscriptionRequest;
use App\Models\Subscription;
use App\Models\User;
use App\Services\Interfaces\SubscriptionServiceInterface;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    private $subscriptionService;

    public function __construct(SubscriptionServiceInterface $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function users(User $user)
    {
        return $this->handleServiceCall(function () use ($user) {
            return $this->subscriptionService->users($user->id);
        });
    }

    public function teams(User $user)
    {
        return $this->handleServiceCall(function () use ($user) {
            return $this->subscriptionService->teams($user->id);
        });
    }

    public function create(CreateSubscriptionRequest $request)
    {
        return $this->handleServiceCall(function () use ($request) {
            return $this->subscriptionService->create($request);
        });
    }

    public function delete(Subscription $subscription)
    {
        return $this->handleServiceCall(function () use ($subscription) {
            return $this->subscriptionService->delete($subscription);
        });
    }
}
