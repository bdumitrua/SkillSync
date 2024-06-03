<?php

namespace App\Services\Post\Interfaces;

use App\DTO\Post\CreatePostCommentDTO;
use App\Http\Requests\Post\CreatePostCommentRequest;
use App\Models\PostComment;
use Illuminate\Http\Resources\Json\JsonResource;

interface PostCommentServiceInterface
{
    /**
     * @param int $postId
     * 
     * @return JsonResource
     */
    public function post(int $postId): JsonResource;

    /**
     * @param int $postId
     * @param CreatePostCommentDTO $createPostCommentDTO
     * 
     * @return void
     */
    public function create(int $postId, CreatePostCommentDTO $createPostCommentDTO): void;

    /**
     * @param PostComment $postComment
     * 
     * @return void
     */
    public function delete(PostComment $postComment): void;
}
