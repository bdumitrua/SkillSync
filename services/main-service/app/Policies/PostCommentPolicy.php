<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\User;
use App\Models\PostComment;

class PostCommentPolicy
{
    /**
     * Determine whether the user can delete the model.
     * 
     * @see DELETE_POST_COMMENT_GATE
     */
    public function deletePostComment(User $user, PostComment $postComment): Response
    {
        return $user->id === $postComment->user_id
            ? Response::allow()
            : Response::deny("Access denied.");
    }
}
