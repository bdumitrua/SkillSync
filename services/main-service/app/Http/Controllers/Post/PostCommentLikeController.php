<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\PostComment;
use App\Services\Post\Interfaces\PostCommentLikeServiceInterface;
use Illuminate\Http\Request;

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
            return $this->postCommentLikeService->create($postComment->id);
        });
    }

    public function delete(PostComment $postComment)
    {
        return $this->handleServiceCall(function () use ($postComment) {
            return $this->postCommentLikeService->delete($postComment->id);
        });
    }
}
