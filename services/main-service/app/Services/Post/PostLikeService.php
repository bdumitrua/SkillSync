<?php

namespace App\Services\Post;

use App\Models\Post;
use App\Models\User;
use App\Repositories\Post\Interfaces\PostLikeRepositoryInterface;
use App\Services\Post\Interfaces\PostLikeServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class PostLikeService implements PostLikeServiceInterface
{
    protected $postLikeRepository;
    protected ?int $authorizedUserId;

    public function __construct(PostLikeRepositoryInterface $postLikeRepository)
    {
        $this->postLikeRepository = $postLikeRepository;
        $this->authorizedUserId = Auth::id();
    }

    public function show(int $postId): JsonResource
    {
        return JsonResource::collection(
            $this->postLikeRepository->getByPostId($postId)
        );
    }

    public function user(int $userId): JsonResource
    {
        return JsonResource::collection(
            $this->postLikeRepository->getByUserId($userId)
        );
    }

    public function create(Post $post): void
    {
        //
    }

    public function delete(Post $post): void
    {
        //
    }
}
