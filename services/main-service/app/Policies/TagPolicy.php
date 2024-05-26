<?php

namespace App\Policies;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

class TagPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function createTag(User $user, string $entityType, int $entityId): bool
    {
        return $this->getRights($user, $entityType, $entityId);
    }

    /**
     * Determine whether the user can delete the model.
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
            return Gate::allows('touchTeamTags', $entityId);
        }

        if ($entityType === config('entities.post')) {
            return Gate::allows('createPost', $entityType, $entityId);
        }

        return false;
    }
}
