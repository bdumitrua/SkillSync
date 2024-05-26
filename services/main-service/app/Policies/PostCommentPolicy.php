<?php

namespace App\Policies;

use App\Models\PostComment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostCommentPolicy
{
    /**
     * Determine whether the user can delete the model.
     * 
     * @see DELETE_POST_COMMENT_GATE
     */
    public function deletePostComment(User $user, PostComment $postComment): bool
    {
        return $user->id === $postComment->user_id;
    }
}
