<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Subscription;
use App\DTO\SubscriptionDTO;

interface SubscriptionRepositoryInterface
{
    /**
     * @param SubscriptionDTO $subscriptionDTO
     * 
     * @return Subscription|null
     */
    public function getByDTO(SubscriptionDTO $subscriptionDTO): ?Subscription;

    /**
     * @param int $userId
     * 
     * @return Collection
     */
    public function getUserSubscriptions(int $userId): Collection;

    /**
     * @param int $userId
     * 
     * @return Collection
     */
    public function getUserSubscribers(int $userId): Collection;

    /**
     * @param int $teamId
     * 
     * @return Collection
     */
    public function getTeamSubscribers(int $teamId): Collection;

    /**
     * @param int $userId
     * 
     * @return Collection
     */
    public function getUsersByUserId(int $userId): Collection;

    /**
     * @param int $userId
     * 
     * @return Collection
     */
    public function getTeamsByUserId(int $userId): Collection;

    /**
     * @param int $subscriberId
     * @param int $targetUserId
     * 
     * @return bool
     */
    public function isSubscribedToUser(int $subscriberId, int $targetUserId): bool;

    /**
     * @param int $userId
     * @param int $teamId
     * 
     * @return bool
     */
    public function isSubscribedToTeam(int $userId, int $teamId): bool;

    /**
     * @param SubscriptionDTO $dto
     * 
     * @return void
     */
    public function create(SubscriptionDTO $dto): void;

    /**
     * @param Subscription $subscription
     * 
     * @return void
     */
    public function delete(Subscription $subscription): void;
}
