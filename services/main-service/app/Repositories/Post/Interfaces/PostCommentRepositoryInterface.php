<?php

namespace App\Repositories\Post\Interfaces;

use App\Models\PostComment;
use Illuminate\Database\Eloquent\Collection;

interface PostCommentRepositoryInterface
{
    /**
     * @param int $postCommentId
     * 
     * @return PostComment|null
     */
    public function getById(int $postCommentId): ?PostComment;

    /**
     * @param int $postId
     * 
     * @return Collection
     */
    public function getByPostId(int $postId): Collection;
}
