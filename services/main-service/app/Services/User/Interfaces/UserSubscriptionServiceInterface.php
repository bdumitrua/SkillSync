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
     * @return Response
     */
    public function subscribe(User $user): Response;

    /**
     * @param User $user
     * 
     * @return Response
     */
    public function unsubscribe(User $user): Response;
}
