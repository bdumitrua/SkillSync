<?php

namespace App\Services\Post\Interfaces;

use App\Models\PostComment;

interface PostCommentLikeServiceInterface
{
    /**
     * @param PostComment $postComment
     * 
     * @return void
     */
    public function create(PostComment $postComment): void;

    /**
     * @param PostComment $postComment
     * 
     * @return void
     */
    public function delete(PostComment $postComment): void;
}
