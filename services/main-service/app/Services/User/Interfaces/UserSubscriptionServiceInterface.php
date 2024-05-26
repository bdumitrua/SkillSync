<?php

namespace App\Services\User\Interfaces;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

interface UserSubscriptionServiceInterface
{
    /**
     * @param User $user
     * 
     * @return JsonResource
     */
    public function subscribers(User $user): JsonResource;

    /**
     * @param User $user
     * 
     * @return JsonResource
     */
    public function subscriptions(User $user): JsonResource;

    /**
     * @param User $user
     * 
     * @return void
     */
    public function subscribe(User $user): void;

    /**
     * @param User $user
     * 
     * @return void
     */
    public function unsubscribe(User $user): void;
}
