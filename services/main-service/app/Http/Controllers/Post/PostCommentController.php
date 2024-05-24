<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostComment;
use App\Services\Post\Interfaces\PostCommentServiceInterface;
use Illuminate\Http\Request;

class PostCommentController extends Controller
{
    protected $postCommentService;

    public function __construct(PostCommentServiceInterface $postCommentService)
    {
        $this->postCommentService = $postCommentService;
    }

    public function show(Post $post)
    {
        return $this->handleServiceCall(function () use ($post) {
            return $this->postCommentService->show($post->id);
        });
    }

    public function create(Post $post)
    {
        return $this->handleServiceCall(function () use ($post) {
            return $this->postCommentService->create($post->id);
        });
    }

    public function delete(PostComment $postComment)
    {
        return $this->handleServiceCall(function () use ($postComment) {
            return $this->postCommentService->delete($postComment);
        });
    }
}
