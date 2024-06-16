<?php

namespace App\Policies;

use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;
use App\Repositories\Team\Interfaces\TeamMemberRepositoryInterface;
use App\Models\User;
use App\Models\Team;
use App\Models\Post;

class PostPolicy
{
    protected $teamMemberRepository;

    public function __construct(
        TeamMemberRepositoryInterface $teamMemberRepository,
    ) {
        $this->teamMemberRepository = $teamMemberRepository;
    }

    /**
     * Determine whether the user can create the post.
     */
    public function create(User $user, string $entityType, int $entityId): Response
    {
        return $this->getRights($user, $entityType, $entityId);
    }

    /**
     * Determine whether the user can update the post.
     */
    public function update(User $user, Post $post): Response
    {
        return $this->getRights($user, $post->entity_type, $post->entity_id);
    }

    /**
     * Determine whether the user can delete the post.
     */
    public function delete(User $user, Post $post): Response
    {
        return $this->getRights($user, $post->entity_type, $post->entity_id);
    }

    private function getRights(User $user, string $entityType, int $entityId): Response
    {
        if ($entityType === config('entities.user')) {
            return $user->id === $entityId
                ? Response::allow()
                : Response::deny("Access denied.");
        }

        if ($entityType === config('entities.team')) {
            return Gate::inspect(TOUCH_TEAM_POSTS_GATE, [Team::class, $entityId]);
        }

        return Response::deny("Unauthorized action.");
    }
}
