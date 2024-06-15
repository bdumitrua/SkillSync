<?php

namespace App\Http\Controllers\Post;

use Illuminate\Http\Request;
use App\Services\Post\Interfaces\PostCommentLikeServiceInterface;
use App\Models\PostComment;
use App\Http\Controllers\Controller;

class PostCommentLikeController extends Controller
{
    protected $postCommentLikeService;

    public function __construct(PostCommentLikeServiceInterface $postCommentLikeService)
    {
        $this->postCommentLikeService = $postCommentLikeService;
    }

    public function create(PostComment $postComment)
    {
        return $this->handleServiceCall(function () use ($postComment) {
            return $this->postCommentLikeService->create($postComment);
        });
    }

    public function delete(PostComment $postComment)
    {
        return $this->handleServiceCall(function () use ($postComment) {
            return $this->postCommentLikeService->delete($postComment);
        });
    }
}
