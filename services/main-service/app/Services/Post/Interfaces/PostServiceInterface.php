<?php

namespace App\Services\Post\Interfaces;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;
use App\Models\Team;
use App\Models\Post;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Requests\Post\CreatePostRequest;

interface PostServiceInterface
{
    /**
     * @return JsonResource
     */
    public function index(): JsonResource;

    /**
     * @return JsonResource
     */
    public function feed(): JsonResource;

    /**
     * @param Post $post
     * 
     * @return JsonResource
     */
    public function show(Post $post): JsonResource;

    /**
     * @param int $userId
     * 
     * @return JsonResource
     */
    public function user(int $userId): JsonResource;

    /**
     * @param int $teamId
     * 
     * @return JsonResource
     */
    public function team(int $teamId): JsonResource;

    /**
     * @param CreatePostRequest $request
     * 
     * @return void
     */
    public function create(CreatePostRequest $request): void;

    /**
     * @param Post $post
     * @param UpdatePostRequest $request
     * 
     * @return void
     */
    public function update(Post $post, UpdatePostRequest $request): void;

    /**
     * @param Post $post
     * 
     * @return void
     */
    public function delete(Post $post): void;
}
