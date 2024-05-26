<?php

namespace App\Services\Post\Interfaces;

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
     * @param CreatePostCommentRequest $request
     * 
     * @return void
     */
    public function create(int $postId, CreatePostCommentRequest $request): void;

    /**
     * @param PostComment $postComment
     * 
     * @return void
     */
    public function delete(PostComment $postComment): void;
}
