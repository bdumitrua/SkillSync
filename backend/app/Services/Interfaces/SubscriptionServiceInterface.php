<?php

namespace App\Services\Interfaces;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
use App\Models\Team;
use App\Models\Subscription;
use App\Http\Requests\SubscriptionRequest;
use App\DTO\SubscriptionDTO;

interface SubscriptionServiceInterface
{
    /**
     * @param int $userId
     * 
     * @return JsonResource
     */
    public function users(int $userId): JsonResource;

    /**
     * @param int $userId
     * 
     * @return JsonResource
     */
    public function teams(int $userId): JsonResource;

    /**
     * @param User $user
     * 
     * @return JsonResource
     */
    public function user(User $user): JsonResource;

    /**
     * @param Team $team
     * 
     * @return JsonResource
     */
    public function team(Team $team): JsonResource;

    /**
     * @param SubscriptionDTO $subscriptionDTO
     * 
     * @return void
     */
    public function create(SubscriptionDTO $subscriptionDTO): void;

    /**
     * @param SubscriptionDTO $subscriptionDTO
     * 
     * @return void
     */
    public function delete(SubscriptionDTO $subscriptionDTO): void;
}
