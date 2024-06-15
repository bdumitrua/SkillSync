<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\Interfaces\SubscriptionServiceInterface;
use App\Models\User;
use App\Models\Team;
use App\Models\Subscription;
use App\Http\Requests\SubscriptionRequest;
use App\DTO\SubscriptionDTO;

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

    public function user(User $user)
    {
        return $this->handleServiceCall(function () use ($user) {
            return $this->subscriptionService->user($user);
        });
    }

    public function team(Team $team)
    {
        return $this->handleServiceCall(function () use ($team) {
            return $this->subscriptionService->team($team);
        });
    }

    public function create(SubscriptionRequest $request)
    {
        $this->patchRequestEntityType($request);
        $SubscriptionDTO = $this->createSubscriptionDTO($request);

        return $this->handleServiceCall(function () use ($SubscriptionDTO) {
            return $this->subscriptionService->create($SubscriptionDTO);
        });
    }

    public function delete(SubscriptionRequest $request)
    {
        $this->patchRequestEntityType($request);
        $subscriptionDTO = $this->createSubscriptionDTO($request);

        return $this->handleServiceCall(function () use ($subscriptionDTO) {
            return $this->subscriptionService->delete($subscriptionDTO);
        });
    }

    protected function createSubscriptionDTO(SubscriptionRequest $request): SubscriptionDTO
    {
        return (new SubscriptionDTO())
            ->setSubscriberId(Auth::id())
            ->setEntityType($request->entityType)
            ->setEntityId($request->entityId);
    }
}
