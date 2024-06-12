<?php

namespace App\Services\Post\Interfaces;

interface PostCommentLikeServiceInterface
{
    /**
     * @param int $postCommentId
     * 
     * @return void
     */
    public function create(int $postCommentId): void;

    /**
     * @param int $postCommentId
     * 
     * @return void
     */
    public function delete(int $postCommentId): void;
}
