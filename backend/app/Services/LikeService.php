<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\AttachEntityData;
use App\Services\Interfaces\LikeServiceInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Repositories\Project\Interfaces\ProjectRepositoryInterface;
use App\Repositories\Post\Interfaces\PostRepositoryInterface;
use App\Repositories\Post\Interfaces\PostCommentRepositoryInterface;
use App\Repositories\Interfaces\LikeRepositoryInterface;
use App\Models\User;
use App\Models\Project;
use App\Models\PostComment;
use App\Models\Post;
use App\Models\Like;
use App\Models\Interfaces\Likeable;
use App\Http\Resources\LikeResource;
use App\Exceptions\NotFoundException;
use App\DTO\LikeDTO;

class LikeService implements LikeServiceInterface
{
    use AttachEntityData;

    protected $userRepository;
    protected $teamRepository;
    protected $likeRepository;
    protected $postRepository;
    protected $projectRepository;
    protected $postCommentRepository;
    protected ?int $authorizedUserId;

    public function __construct(
        UserRepositoryInterface $userRepository,
        TeamRepositoryInterface $teamRepository,
        LikeRepositoryInterface $likeRepository,
        PostRepositoryInterface $postRepository,
        ProjectRepositoryInterface $projectRepository,
        PostCommentRepositoryInterface $postCommentRepository,
    ) {
        $this->userRepository = $userRepository;
        $this->teamRepository = $teamRepository;
        $this->likeRepository = $likeRepository;
        $this->postRepository = $postRepository;
        $this->projectRepository = $projectRepository;
        $this->postCommentRepository = $postCommentRepository;
        $this->authorizedUserId = Auth::id();
    }

    public function user(User $user): JsonResource
    {
        $likes = $this->likeRepository->getByUser($user);
        $likes = $this->assembleLikesLikeableData($likes);

        return LikeResource::collection($likes);
    }

    public function post(Post $post): JsonResource
    {
        $likes = $this->likeRepository->getByPost($post);
        $likes = $this->assembleLikesUserData($likes);

        return LikeResource::collection($likes);
    }

    public function create(LikeDTO $likeDTO): void
    {
        $likeableModel = $this->likeRepository->getLikeableByDTO($likeDTO);
        if (empty($likeableModel)) {
            throw new NotFoundException('Model to like');
        }

        Gate::authorize(
            'create',
            [
                Like::class,
                $likeDTO->likeableType,
                $likeDTO->likeableId
            ]
        );

        $this->likeRepository->create($this->authorizedUserId, $likeableModel);
    }

    public function delete(LikeDTO $likeDTO): void
    {
        $like = $this->likeRepository->getByDTO($likeDTO);
        if (empty($like)) {
            throw new NotFoundException('Like');
        }

        Gate::authorize('delete', [Like::class, $like]);

        $this->likeRepository->delete($like);
    }

    protected function assembleLikesUserData(Collection $likes): Collection
    {
        $this->setCollectionEntityData($likes, 'user_id', 'userData', $this->userRepository);

        return $likes;
    }

    protected function assembleLikesLikeableData(Collection $likes): Collection
    {
        Log::debug('Assebmling likes likeable data', [
            'likesIds' => $likes->pluck('id')->toArray()
        ]);

        // Anyway likeable models doesn't cache, so this is much easier to do
        $likes = $this->likeRepository->getLikesWithLikeable($likes);

        $likes = $likes->filter(function ($like) {
            /** @var Post|PostComment|Project */
            if ($like->likeable_type === config('entities.postComment')) {
                return false;
            }

            return true; // Оставляем элемент
        });

        foreach ($likes as $like) {
            /** @var Post|PostComment|Project */
            $likeable = $like->likeable;

            if ($likeable->getAuthorType() === config('entities.user')) {
                $likeable->setRelation('author', $this->userRepository->getById($likeable->getAuthorId()));
            } elseif ($likeable->getAuthorType() === config('entities.team')) {
                $likeable->setRelation('author', $this->teamRepository->getById($likeable->getAuthorId()));
            }
        }

        return $likes;
    }
}
