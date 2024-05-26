<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\Tag;
use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

class TagPolicy
{
    /**
     * Determine whether the user can create models.
     * 
     * @see CREATE_TAG_GATE
     */
    public function createTag(User $user, string $entityType, int $entityId): bool
    {
        return $this->getRights($user, $entityType, $entityId);
    }

    /**
     * Determine whether the user can delete the model.
     * 
     * @see DELETE_TAG_GATE
     */
    public function deleteTag(User $user, Tag $tag): bool
    {
        return $this->getRights($user, $tag->entity_type, $tag->entity_id);
    }

    private function getRights(User $user, string $entityType, int $entityId): bool
    {
        if ($entityType === config('entities.user')) {
            return $user->id === $entityId;
        }

        if ($entityType === config('entities.team')) {
            return Gate::allows(TOUCH_TEAM_TAGS_GATE, [Team::class, $entityId]);
        }

        if ($entityType === config('entities.post')) {
            return Gate::allows(CREATE_POST_GATE, [Post::class, $entityType, $entityId]);
        }

        return false;
    }
}
