<?php

namespace App\Services\Post;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\AttachEntityData;
use App\Services\Post\Interfaces\PostServiceInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Repositories\Post\Interfaces\PostRepositoryInterface;
use App\Repositories\Interfaces\TagRepositoryInterface;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use App\Repositories\Interfaces\LikeRepositoryInterface;
use App\Models\Post;
use App\Http\Resources\Post\PostResource;
use App\DTO\Post\UpdatePostDTO;
use App\DTO\Post\CreatePostDTO;

class PostService implements PostServiceInterface
{
    use AttachEntityData;

    protected $tagRepository;
    protected $postRepository;
    protected $userRepository;
    protected $teamRepository;
    protected $likeRepository;
    protected $subscriptionRepository;
    protected ?int $authorizedUserId;

    public function __construct(
        TagRepositoryInterface $tagRepository,
        PostRepositoryInterface $postRepository,
        UserRepositoryInterface $userRepository,
        TeamRepositoryInterface $teamRepository,
        LikeRepositoryInterface $likeRepository,
        SubscriptionRepositoryInterface $subscriptionRepository,

    ) {
        $this->tagRepository = $tagRepository;
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
        $this->teamRepository = $teamRepository;
        $this->likeRepository = $likeRepository;
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
            $subscriptionsToUsers->pluck('author_id')->toArray(),
            $subscriptionsToTeams->pluck('author_id')->toArray()
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

    public function search(string $query): JsonResource
    {
        $queryPosts = $this->postRepository->search($query);
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
        Gate::authorize('create', [Post::class, $createPostDTO->authorType, $createPostDTO->authorId]);

        $this->postRepository->create($createPostDTO);
    }

    public function update(Post $post, UpdatePostDTO $updatePostDTO): void
    {
        Gate::authorize('update', [Post::class, $post]);

        $this->postRepository->update($post, $updatePostDTO);
    }

    public function delete(Post $post): void
    {
        Gate::authorize('delete', [Post::class, $post]);

        $this->postRepository->delete($post);
    }

    protected function assemblePostsData(Collection $posts): Collection
    {
        $this->setPostsAuthorData($posts);
        $this->setPostsTagsData($posts);
        $this->setPostsRights($posts);
        $this->setCollectionIsLiked($posts, 'post', $this->likeRepository);

        return $posts;
    }

    protected function setPostsAuthorData(Collection &$posts): void
    {
        Log::debug("Setting posts author data", [
            'posts' => $posts->pluck('id')->toArray(),
        ]);

        $this->setCollectionMorphData($posts, 'author', 'user', $this->userRepository);
        $this->setCollectionMorphData($posts, 'author', 'team', $this->teamRepository);

        Log::debug("Succesfully setted posts author data", [
            'posts' => $posts->pluck('id')->toArray(),
        ]);
    }

    protected function setPostsTagsData(Collection &$posts): void
    {
        Log::debug("Setting posts tags data", [
            'posts' => $posts->pluck('id')->toArray(),
        ]);

        $postIds = $posts->pluck('id')->unique()->all();
        $postsTags = $this->tagRepository->getByPostIds($postIds)->flatten();

        foreach ($posts as $post) {
            $post->tagsData = $postsTags->where('entity_id', $post->id);
        }

        Log::debug("Succesfully setted posts tags data", [
            'posts' => $posts->pluck('id')->toArray(),
        ]);
    }

    protected function setPostsRights(Collection &$posts): void
    {
        foreach ($posts as $post) {
            $post->canUpdate = Gate::allows('update', [Post::class, $post]);
            $post->canDelete = Gate::allows('delete', [Post::class, $post]);
        }
    }
}
