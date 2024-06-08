<?php

namespace App\Repositories\Post\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use App\Models\PostComment;
use App\DTO\Post\CreatePostCommentDTO;

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

    /**
     * @param CreatePostCommentDTO $dto
     * 
     * @return PostComment
     */
    public function create(CreatePostCommentDTO $dto): PostComment;

    /**
     * @param PostComment $postComment
     * 
     * @return void
     */
    public function delete(PostComment $postComment): void;
}
