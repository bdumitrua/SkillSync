<?php

namespace App\Services\Post;

use App\DTO\Post\CreatePostDTO;
use App\DTO\Post\UpdatePostDTO;
use App\Services\Post\Interfaces\PostServiceInterface;
use App\Models\Post;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Resources\Post\PostResource;
use App\Models\Team;
use App\Models\User;
use App\Repositories\Interfaces\TagRepositoryInterface;
use App\Repositories\Post\Interfaces\PostRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Traits\CreateDTO;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostService implements PostServiceInterface
{
    use CreateDTO;

    protected $tagRepository;
    protected $postRepository;
    protected $userRepository;
    protected $teamRepository;
    protected ?int $authorizedUserId;

    public function __construct(
        TagRepositoryInterface $tagRepository,
        PostRepositoryInterface $postRepository,
        UserRepositoryInterface $userRepository,
        TeamRepositoryInterface $teamRepository,

    ) {
        $this->tagRepository = $tagRepository;
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
        $this->teamRepository = $teamRepository;
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
        $feedPosts = $this->postRepository->feed($this->authorizedUserId);
        $feedPosts = $this->assemblePostsData($feedPosts);

        return PostResource::collection($feedPosts);
    }

    public function show(Post $post): JsonResource
    {
        $post = $this->postRepository->getById($post->id);
        $post = $this->assemblePostsData(new Collection([$post]))->first();

        return new PostResource($post);
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

    public function create(CreatePostRequest $request): void
    {
        $this->patchCreatePostRequestData($request);
        Gate::authorize(CREATE_POST_GATE, [Post::class, $request->entityType, $request->entityId]);

        /** @var  CreatePostDTO */
        $createPostDTO = $this->createDTO($request, CreatePostDTO::class);

        $this->postRepository->create($createPostDTO);
    }

    public function update(Post $post, UpdatePostRequest $request): void
    {
        Gate::authorize(UPDATE_POST_GATE, [Post::class, $post]);

        $updatePostDTO = $this->createDTO($request, UpdatePostDTO::class);
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

        return $posts;
    }

    protected function setPostsEntityData(Collection &$posts): void
    {
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
    }

    protected function setPostsTagsData(Collection &$posts): void
    {
        $postIds = $posts->pluck('id')->unique()->all();
        $postsTags = $this->tagRepository->getByPostIds($postIds);

        foreach ($posts as $post) {
            $post->tagsData = $postsTags->where('entity_id', $post->id)->first();
        }
    }

    protected function patchCreatePostRequestData(CreatePostRequest &$request): void
    {
        $entitiesPath = config('entities');

        $request->merge([
            'entityType' => $entitiesPath[$request->entityType]
        ]);
    }
}
