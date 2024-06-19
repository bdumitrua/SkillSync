<?php

namespace App\Http\Controllers\Post;

use Illuminate\Http\Request;
use App\Services\Post\Interfaces\PostServiceInterface;
use App\Models\User;
use App\Models\Team;
use App\Models\Post;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    private $postService;

    public function __construct(PostServiceInterface $postService)
    {
        $this->postService = $postService;
    }

    public function index()
    {
        return $this->handleServiceCall(function () {
            return $this->postService->index();
        });
    }

    public function feed()
    {
        return $this->handleServiceCall(function () {
            return $this->postService->feed();
        });
    }

    public function show(Post $post)
    {
        return $this->handleServiceCall(function () use ($post) {
            return $this->postService->show($post);
        });
    }

    public function search(SearchRequest $request)
    {
        return $this->handleServiceCall(function () use ($request) {
            return $this->postService->search($request->input('query'));
        });
    }

    public function user(User $user)
    {
        return $this->handleServiceCall(function () use ($user) {
            return $this->postService->user($user->id);
        });
    }

    public function team(Team $team)
    {
        return $this->handleServiceCall(function () use ($team) {
            return $this->postService->team($team->id);
        });
    }

    public function create(CreatePostRequest $request)
    {
        $this->patchRequestEntityType($request);

        $createPostDTO = $request->createDTO();

        return $this->handleServiceCall(function () use ($createPostDTO) {
            return $this->postService->create($createPostDTO);
        });
    }

    public function update(Post $post, UpdatePostRequest $request)
    {
        $updatePostDTO = $request->createDTO();

        return $this->handleServiceCall(function () use ($post, $updatePostDTO) {
            return $this->postService->update($post, $updatePostDTO);
        });
    }

    public function delete(Post $post)
    {
        return $this->handleServiceCall(function () use ($post) {
            return $this->postService->delete($post);
        });
    }
}
