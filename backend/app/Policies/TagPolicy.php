<?php

namespace App\Policies;

use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;
use App\Models\User;
use App\Models\Team;
use App\Models\Tag;
use App\Models\Post;

class TagPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, string $entityType, int $entityId): Response
    {
        return $this->getRights($user, $entityType, $entityId);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Tag $tag): Response
    {
        return $this->getRights($user, $tag->entity_type, $tag->entity_id);
    }

    private function getRights(User $user, string $entityType, int $entityId): Response
    {
        if ($entityType === config('entities.user')) {
            return $user->id === $entityId
                ? Response::allow()
                : Response::deny("You can't modify other users' tags");
        }

        if ($entityType === config('entities.team')) {
            return Gate::inspect(TOUCH_TEAM_TAGS_GATE, [Team::class, $entityId]);
        }

        if ($entityType === config('entities.post')) {
            return Gate::inspect('tag', [Post::class, $entityId]);
        }

        return Response::deny("Unauthorized action.");
    }
}
