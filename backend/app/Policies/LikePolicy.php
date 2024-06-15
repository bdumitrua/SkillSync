<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\User;
use App\Models\Like;

class LikePolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, string $likeableType, int $likeableId): Response
    {
        $alreadyLiked = $user->likeable($likeableType, $likeableId)->exists();
        $className = class_basename($likeableType);

        return $alreadyLiked
            ? Response::deny("You already liked this $className")
            : Response::allow();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Like $like): Response
    {
        return $user->id === $like->user_id
            ? Response::allow()
            : Response::deny("Access denied.");
    }
}
