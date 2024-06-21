<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Project\Interfaces\ProjectRepositoryInterface;
use App\Repositories\Post\Interfaces\PostRepositoryInterface;
use App\Repositories\Post\Interfaces\PostCommentRepositoryInterface;
use App\Repositories\Interfaces\LikeRepositoryInterface;
use App\Models\User;
use App\Models\Post;
use App\Models\Like;
use App\Models\Interfaces\Likeable;
use App\DTO\LikeDTO;

class LikeRepository implements LikeRepositoryInterface
{
    protected $postRepository;
    protected $projectRepository;
    protected $postCommentRepository;

    public function __construct(
        PostRepositoryInterface $postRepository,
        ProjectRepositoryInterface $projectRepository,
        PostCommentRepositoryInterface $postCommentRepository,
    ) {
        $this->postRepository = $postRepository;
        $this->projectRepository = $projectRepository;
        $this->postCommentRepository = $postCommentRepository;
    }

    public function getByUser(User $user): Collection
    {
        return $user->likes()->get();
    }

    public function getByPost(Post $post): Collection
    {
        return $post->likes()->get();
    }

    public function getByDTO(LikeDTO $likeDTO): ?Like
    {
        return Like::where('user_id', '=', $likeDTO->userId)
            ->where('likeable_type', '=', $likeDTO->likeableType)
            ->where('likeable_id', '=', $likeDTO->likeableId)
            ->first();
    }

    public function getLikeableByDTO(LikeDTO $likeDTO): ?Likeable
    {
        if ($likeDTO->likeableType === config('entities.post')) {
            return $this->postRepository->getById($likeDTO->likeableId);
        } elseif ($likeDTO->likeableType === config('entities.postComment')) {
            return $this->postCommentRepository->getById($likeDTO->likeableId);
        } elseif ($likeDTO->likeableType === config('entities.project')) {
            return $this->projectRepository->getById($likeDTO->likeableId);
        }

        Log::warning("likeableType from LikeDTO didn't match any type of repository", [
            'LikeDTO' => $likeDTO->toArray()
        ]);
        return null;
    }

    public function getByUserAndCommentsIds(int $userId, array $postCommentsIds): Collection
    {
        return Like::where('user_id', '=', $userId)
            ->where('likeable_type', '=', config('entities.postComment'))
            ->whereIn('likeable_id', $postCommentsIds)
            ->get();
    }

    public function getByUserAndProjectsIds(int $userId, array $projectIds): Collection
    {
        return Like::where('user_id', '=', $userId)
            ->where('likeable_type', '=', config('entities.project'))
            ->whereIn('likeable_id', $projectIds)
            ->get();
    }

    public function getByUserAndPostsIds(int $userId, array $postsIds): Collection
    {
        return Like::where('user_id', '=', $userId)
            ->where('likeable_type', '=', config('entities.post'))
            ->whereIn('likeable_id', $postsIds)
            ->get();
    }

    /**
     * @param int $userId
     * @param Likeable $likeableModel
     * 
     * @return void
     */
    public function create(int $userId, Likeable $likeableModel): void
    {
        $likeableModel->likes()->create(['user_id' => $userId]);
        $likeableModel->incrementLikesCount();
    }

    /**
     * @param Like $like
     * 
     * @return void
     */
    public function delete(Like $like): void
    {
        $like->likeable()->first()->decrementLikesCount();
        $like->delete();
    }
}
