<?php

namespace App\Http\Controllers;

use App\DTO\User\CreateSubscriptionDTO;
use App\Http\Requests\CreateSubscriptionRequest;
use App\Models\Subscription;
use App\Models\User;
use App\Services\Interfaces\SubscriptionServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        /** @var CreateSubscriptionDTO */
        $createSubscriptionDTO = $request->createDTO();
        $createSubscriptionDTO->setUserId(Auth::id());

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
