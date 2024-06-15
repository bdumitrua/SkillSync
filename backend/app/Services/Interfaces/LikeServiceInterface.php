<?php

namespace App\Services\Interfaces;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
use App\Models\Post;
use App\Models\Like;
use App\Http\Requests\LikeRequest;
use App\DTO\LikeDTO;

interface LikeServiceInterface
{
    /**
     * @param User $user
     * 
     * @return JsonResource
     */
    public function user(User $user): JsonResource;

    /**
     * @param Post $post
     * 
     * @return JsonResource
     */
    public function post(Post $post): JsonResource;

    /**
     * @param LikeDTO $likeDTO
     * 
     * @return void
     */
    public function create(LikeDTO $likeDTO): void;

    /**
     * @param LikeDTO $likeDTO
     * 
     * @return void
     */
    public function delete(LikeDTO $likeDTO): void;
}
