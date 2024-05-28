<?php

namespace App\Policies;

use App\Models\Subscription;
use App\Models\User;
use App\Repositories\Team\Interfaces\TeamMemberRepositoryInterface;
use Illuminate\Auth\Access\Response;

class SubscriptionPolicy
{
    protected $teamMemberRepository;
    protected $entityTypes;

    public function __construct(TeamMemberRepositoryInterface $teamMemberRepository)
    {
        $this->teamMemberRepository = $teamMemberRepository;
        $this->entityTypes = [
            config('entities.user'),
            config('entities.team')
        ];
    }

    /**
     * Determine whether the user can subscribe to entity.
     * 
     * @see SUBSCRIBE_TO_ENTITY_GATE
     */
    public function subscribeToEntity(User $user, string $entityType, int $entityId): Response
    {
        if ($entityType === config('entities.user')) {
            if ($user->id === $entityId) {
                return Response::denyWithStatus(400, "You can't subscribe to yourself");
            }
        }

        // if ($entityType === config('entities.team')) {
        //     if ($this->teamMemberRepository->userIsMember($entityId, $user->id)) {
        //         return Response::denyWithStatus(400, "You can't subscribe to team, if you're member of it.");
        //     }
        // }


        return Response::allow();
    }

    /**
     * Determine whether the user can unsubscribe from entity.
     * 
     * @see UNSUBSCRIBE_FROM_ENTITY_GATE
     */
    public function unsubscribeFromEntity(User $user, string $entityType, int $entityId): Response
    {
        if ($entityType === config('entities.user')) {
            if ($user->id === $entityId) {
                return Response::denyWithStatus(400, "You can't subscribe to yourself");
            }
        }

        return Response::allow();
    }
}
