<?php

namespace App\Services\Post\Interfaces;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;
use App\Models\Team;
use App\Models\Post;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Requests\Post\CreatePostRequest;
use App\DTO\Post\UpdatePostDTO;
use App\DTO\Post\CreatePostDTO;

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
     * @param string $query
     * 
     * @return JsonResource
     */
    public function search(string $query): JsonResource;

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
     * @param CreatePostDTO $createPostDTO
     * 
     * @return void
     */
    public function create(CreatePostDTO $createPostDTO): void;

    /**
     * @param Post $post
     * @param UpdatePostDTO $updatePostDTO
     * 
     * @return void
     */
    public function update(Post $post, UpdatePostDTO $updatePostDTO): void;

    /**
     * @param Post $post
     * 
     * @return void
     */
    public function delete(Post $post): void;
}
