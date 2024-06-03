<?php

namespace App\Http\Controllers\Post;

use App\DTO\Post\CreatePostCommentDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\CreatePostCommentRequest;
use App\Models\Post;
use App\Models\PostComment;
use App\Services\Post\Interfaces\PostCommentServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostCommentController extends Controller
{
    protected $postCommentService;

    public function __construct(PostCommentServiceInterface $postCommentService)
    {
        $this->postCommentService = $postCommentService;
    }

    public function post(Post $post)
    {
        return $this->handleServiceCall(function () use ($post) {
            return $this->postCommentService->post($post->id);
        });
    }

    public function create(Post $post, CreatePostCommentRequest $request)
    {
        /** @var CreatePostCommentDTO */
        $createPostCommentDTO = $request->createDTO();
        $createPostCommentDTO->setPostId($post->id)->setUserId(Auth::id());

        return $this->handleServiceCall(function () use ($post, $createPostCommentDTO) {
            return $this->postCommentService->create($post->id, $createPostCommentDTO);
        });
    }

    public function delete(PostComment $postComment)
    {
        return $this->handleServiceCall(function () use ($postComment) {
            return $this->postCommentService->delete($postComment);
        });
    }
}
