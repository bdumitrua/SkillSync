<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Services\Post\Interfaces\PostLikeServiceInterface;
use Illuminate\Http\Request;

class PostLikeController extends Controller
{
    protected $postLikeService;

    public function __construct(PostLikeServiceInterface $postLikeService)
    {
        $this->postLikeService = $postLikeService;
    }

    public function show(Post $post)
    {
        return $this->handleServiceCall(function () use ($post) {
            return $this->postLikeService->show($post->id);
        });
    }

    public function user(User $user)
    {
        return $this->handleServiceCall(function () use ($user) {
            return $this->postLikeService->user($user->id);
        });
    }

    public function create(Post $post)
    {
        return $this->handleServiceCall(function () use ($post) {
            return $this->postLikeService->create($post);
        });
    }

    public function delete(Post $post)
    {
        return $this->handleServiceCall(function () use ($post) {
            return $this->postLikeService->delete($post);
        });
    }
}
