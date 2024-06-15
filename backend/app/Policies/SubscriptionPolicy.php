<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Repositories\Team\Interfaces\TeamMemberRepositoryInterface;
use App\Models\User;
use App\Models\Subscription;

class SubscriptionPolicy
{
    protected $teamMemberRepository;

    public function __construct(TeamMemberRepositoryInterface $teamMemberRepository)
    {
        $this->teamMemberRepository = $teamMemberRepository;
    }

    /**
     * Determine whether the user can subscribe to entity.
     */
    public function create(User $user, string $entityType, int $entityId): Response
    {
        if ($entityType === config('entities.user')) {
            if ($user->id === $entityId) {
                return Response::denyWithStatus(409, "You can't subscribe to yourself");
            }
        }

        if ($entityType === config('entities.team')) {
            if ($this->teamMemberRepository->userIsMember($entityId, $user->id)) {
                return Response::denyWithStatus(409, "You can't subscribe to team, if you're member of it.");
            }
        }

        if ($user->subscriptable($entityType, $entityId)->exists()) {
            $entityName = class_basename($entityType);
            return Response::denyWithStatus(409, "You're already subscribed to this $entityName");
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can unsubscribe from entity.
     */
    public function delete(User $user, Subscription $subscription): Response
    {
        return $user->id === $subscription->subscriber_id
            ? Response::allow()
            : Response::deny("Access denied.");
    }
}
