<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\Interfaces\SubscriptionServiceInterface;
use App\Models\User;
use App\Models\Subscription;
use App\Http\Requests\CreateSubscriptionRequest;
use App\DTO\CreateSubscriptionDTO;

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
        $this->patchRequestEntityType($request);

        $createSubscriptionDTO = (new CreateSubscriptionDTO())
            ->setSubscriberId(Auth::id())
            ->setEntityType($request->entityType)
            ->setEntityId($request->entityId);

        return $this->handleServiceCall(function () use ($createSubscriptionDTO) {
            return $this->subscriptionService->create($createSubscriptionDTO);
        });
    }

    public function delete(Subscription $subscription)
    {
        return $this->handleServiceCall(function () use ($subscription) {
            return $this->subscriptionService->delete($subscription);
        });
    }
}
