<?php

namespace App\Services\Post;

use App\Services\Post\Interfaces\PostServiceInterface;
use App\Models\Post;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Resources\PostResource;
use App\Repositories\Post\Interfaces\PostRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class PostService implements PostServiceInterface
{
    protected $postRepository;
    protected ?int $authorizedUserId;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
        $this->authorizedUserId = Auth::id();
    }

    public function index(): JsonResource
    {
        return PostResource::collection(
            $this->postRepository->getAll()
        );
    }

    public function feed(): JsonResource
    {
        return PostResource::collection(
            $this->postRepository->feed($this->authorizedUserId)
        );
    }

    public function show(Post $post): JsonResource
    {
        return PostResource::collection(
            $this->postRepository->getById($post->id)
        );
    }

    public function user(int $userId): JsonResource
    {
        return PostResource::collection(
            $this->postRepository->getByUserId($userId)
        );
    }

    public function team(int $teamId): JsonResource
    {
        return PostResource::collection(
            $this->postRepository->getByTeamId($teamId)
        );
    }

    public function create(CreatePostRequest $request): void
    {
        // 
    }

    public function update(Post $post, UpdatePostRequest $request): void
    {
        //
    }

    public function delete(Post $post): void
    {
        //
    }
}
