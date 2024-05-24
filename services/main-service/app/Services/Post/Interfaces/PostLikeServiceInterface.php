<?php

namespace App\Services\Post\Interfaces;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
use App\Models\Post;

interface PostLikeServiceInterface
{
    /**
     * @param int $postId
     * 
     * @return JsonResource
     */
    public function show(int $postId): JsonResource;

    /**
     * @param int $userId
     * 
     * @return JsonResource
     */
    public function user(int $userId): JsonResource;

    /**
     * @param Post $post
     * 
     * @return void
     */
    public function create(Post $post): void;

    /**
     * @param Post $post
     * 
     * @return void
     */
    public function delete(Post $post): void;
}
