<?php

namespace App\Services\Post;

use App\Models\Post;
use App\Models\User;
use App\Repositories\Post\Interfaces\PostLikeRepositoryInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Services\Post\Interfaces\PostLikeServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class PostLikeService implements PostLikeServiceInterface
{
    protected $userRepository;
    protected $postLikeRepository;
    protected ?int $authorizedUserId;

    public function __construct(
        UserRepositoryInterface $userRepository,
        PostLikeRepositoryInterface $postLikeRepository,
    ) {
        $this->userRepository = $userRepository;
        $this->postLikeRepository = $postLikeRepository;
        $this->authorizedUserId = Auth::id();
    }

    public function post(int $postId): JsonResource
    {
        $postLikes = $this->postLikeRepository->getByPostId($postId);
        $postLikes = $this->assembleLikesData($postLikes);

        return JsonResource::collection($postLikes);
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

    protected function assembleLikesData(Collection $postLikes): Collection
    {
        $userIds = $postLikes->pluck('user_id')->unique()->all();
        $usersData = $this->userRepository->getByIds($userIds);

        foreach ($postLikes as $like) {
            $like->userData = $usersData->where('id', $like->user_id)->first();
        }

        return $postLikes;
    }
}
