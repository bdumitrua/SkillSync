<?php

namespace App\Services\Post;

use App\DTO\Post\CreatePostDTO;
use App\DTO\Post\UpdatePostDTO;
use App\Services\Post\Interfaces\PostServiceInterface;
use App\Models\Post;
use App\Http\Resources\Post\PostResource;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use App\Repositories\Interfaces\TagRepositoryInterface;
use App\Repositories\Post\Interfaces\PostLikeRepositoryInterface;
use App\Repositories\Post\Interfaces\PostRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class PostService implements PostServiceInterface
{
    protected $tagRepository;
    protected $postRepository;
    protected $userRepository;
    protected $teamRepository;
    protected $postLikeRepository;
    protected $subscriptionRepository;
    protected ?int $authorizedUserId;

    public function __construct(
        TagRepositoryInterface $tagRepository,
        PostRepositoryInterface $postRepository,
        UserRepositoryInterface $userRepository,
        TeamRepositoryInterface $teamRepository,
        PostLikeRepositoryInterface $postLikeRepository,
        SubscriptionRepositoryInterface $subscriptionRepository,

    ) {
        $this->tagRepository = $tagRepository;
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
        $this->teamRepository = $teamRepository;
        $this->postLikeRepository = $postLikeRepository;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->authorizedUserId = Auth::id();
    }

    public function index(): JsonResource
    {
        $posts = $this->postRepository->getAll();
        $posts = $this->assemblePostsData($posts);

        return PostResource::collection($posts);
    }

    public function feed(): JsonResource
    {
        $subscriptionsToUsers = $this->subscriptionRepository->getUsersByUserId($this->authorizedUserId);
        $subscriptionsToTeams = $this->subscriptionRepository->getTeamsByUserId($this->authorizedUserId);

        $feedPosts = $this->postRepository->feed(
            $this->authorizedUserId,
            $subscriptionsToUsers->pluck('entity_id')->toArray(),
            $subscriptionsToTeams->pluck('entity_id')->toArray()
        );

        $feedPosts = $this->assemblePostsData($feedPosts);

        return PostResource::collection($feedPosts);
    }

    public function show(Post $post): JsonResource
    {
        $post = $this->postRepository->getById($post->id);
        $post = $this->assemblePostsData(new Collection([$post]))->first();

        return new PostResource($post);
    }

    public function search(Request $request): JsonResource
    {
        $queryPosts = $this->postRepository->search($request->input('query'));
        $queryPosts = $this->assemblePostsData($queryPosts);

        return PostResource::collection($queryPosts);
    }

    public function user(int $userId): JsonResource
    {
        $userPosts = $this->postRepository->getByUserId($userId);
        $userPosts = $this->assemblePostsData($userPosts);

        return PostResource::collection($userPosts);
    }

    public function team(int $teamId): JsonResource
    {
        $teamPosts = $this->postRepository->getByTeamId($teamId);
        $teamPosts = $this->assemblePostsData($teamPosts);

        return PostResource::collection($teamPosts);
    }

    public function create(CreatePostDTO $createPostDTO): void
    {
        Gate::authorize(CREATE_POST_GATE, [Post::class, $createPostDTO->entityType, $createPostDTO->entityId]);

        $this->postRepository->create($createPostDTO);
    }

    public function update(Post $post, UpdatePostDTO $updatePostDTO): void
    {
        Gate::authorize(UPDATE_POST_GATE, [Post::class, $post]);

        $this->postRepository->update($post, $updatePostDTO);
    }

    public function delete(Post $post): void
    {
        Gate::authorize(DELETE_POST_GATE, [Post::class, $post]);

        $this->postRepository->delete($post);
    }

    protected function assemblePostsData(Collection $posts): Collection
    {
        $this->setPostsEntityData($posts);
        $this->setPostsTagsData($posts);
        $this->setPostsRights($posts);
        $this->setLikesCount($posts);
        $this->setLikeAbility($posts);

        return $posts;
    }

    protected function setPostsEntityData(Collection &$posts): void
    {
        Log::debug("Setting posts entity data", [
            'posts' => $posts->pluck('id')->toArray(),
        ]);

        $userIds = [];
        $teamIds = [];

        /** @var Post */
        foreach ($posts as $post) {
            if ($post->createdByUser()) {
                $userIds[] = $post->entity_id;
            } elseif ($post->createdByTeam()) {
                $teamIds[] = $post->entity_id;
            }
        }

        $userIds = array_unique($userIds);
        $teamIds = array_unique($teamIds);

        $usersData = $this->userRepository->getByIds($userIds);
        $teamsData = $this->teamRepository->getByIds($teamIds);

        /** @var Post */
        foreach ($posts as $post) {
            if ($post->createdByUser()) {
                $post->entityData = $usersData->where('id', $post->entity_id)->first();
            } elseif ($post->createdByTeam()) {
                $post->entityData = $teamsData->where('id', $post->entity_id)->first();
            }
        }

        Log::debug("Succesfully setted posts entity data", [
            'posts' => $posts->pluck('id')->toArray(),
        ]);
    }

    protected function setPostsTagsData(Collection &$posts): void
    {
        Log::debug("Setting posts tags data", [
            'posts' => $posts->pluck('id')->toArray(),
        ]);

        $postIds = $posts->pluck('id')->unique()->all();
        $postsTags = $this->tagRepository->getByPostIds($postIds);

        foreach ($posts as $post) {
            $post->tagsData = isset($postsTags[$post->id]) ? $postsTags[$post->id] : [];
        }

        Log::debug("Succesfully setted posts tags data", [
            'posts' => $posts->pluck('id')->toArray(),
        ]);
    }

    protected function setPostsRights(Collection &$posts): void
    {
        foreach ($posts as $post) {
            $post->canUpdate = Gate::allows(UPDATE_POST_GATE, [Post::class, $post]);
            $post->canDelete = Gate::allows(DELETE_POST_GATE, [Post::class, $post]);
        }
    }

    protected function setLikesCount(Collection &$posts): void
    {
        /** @var Post */
        foreach ($posts as $post) {
            $post->likesCount = $post->likes()->count();
        }
    }

    protected function setLikeAbility(Collection &$posts): void
    {
        $postsIds = $posts->pluck('id')->toArray();
        $postsLikes = $this->postLikeRepository->getByUserAndPostsIds($this->authorizedUserId, $postsIds);

        $postsLikesKeyedByPostId = $postsLikes->keyBy('post_id');

        foreach ($posts as $post) {
            $post->isLiked = isset($postsLikesKeyedByPostId[$post->id]);
        }
    }
}
