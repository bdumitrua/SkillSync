<?php

namespace App\Services\Interfaces;

use App\DTO\CreateSubscriptionDTO;
use App\Models\Subscription;
use App\Http\Requests\CreateSubscriptionRequest;
use Illuminate\Http\Resources\Json\JsonResource;

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
     * @param CreateSubscriptionDTO $createSubscriptionDTO
     * 
     * @return void
     */
    public function create(CreateSubscriptionDTO $createSubscriptionDTO): void;

    /**
     * @param Subscription $subscription
     * 
     * @return void
     */
    public function delete(Subscription $subscription): void;
}
