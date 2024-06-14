<?php

namespace App\Services\Post;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\SetAdditionalData;
use App\Services\Post\Interfaces\PostLikeServiceInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Repositories\Post\Interfaces\PostLikeRepositoryInterface;
use App\Models\User;
use App\Models\Post;
use App\Exceptions\LikeException;

class PostLikeService implements PostLikeServiceInterface
{
    use SetAdditionalData;

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

    /**
     * @throws LikeException
     */
    public function create(Post $post): void
    {
        $liked = $this->postLikeRepository->create($post, $this->authorizedUserId);

        if (!$liked) {
            throw new LikeException("You already liked this post.");
        }
    }

    /**
     * @throws LikeException
     */
    public function delete(Post $post): void
    {
        $unliked = $this->postLikeRepository->delete($post, $this->authorizedUserId);

        if (!$unliked) {
            throw new LikeException("You haven't liked this post.");
        }
    }

    protected function assembleLikesData(Collection $postLikes): Collection
    {
        $this->setCollectionEntityData($postLikes, 'user_id', 'userData', $this->userRepository);

        return $postLikes;
    }
}
