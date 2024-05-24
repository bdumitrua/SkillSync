<?php

namespace App\Services\Post;

use App\Http\Resources\PostCommentResource;
use App\Models\PostComment;
use App\Repositories\Post\Interfaces\PostCommentRepositoryInterface;
use App\Services\Post\Interfaces\PostCommentServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

class PostCommentService implements PostCommentServiceInterface
{
    protected $postCommentRepository;

    public function __construct(PostCommentRepositoryInterface $postCommentRepository)
    {
        $this->postCommentRepository = $postCommentRepository;
    }

    public function show(int $postId): JsonResource
    {
        return PostCommentResource::collection(
            $this->postCommentRepository->getByPostId($postId)
        );
    }

    public function create(int $postId): void
    {
        //
    }

    public function delete(PostComment $postComment): void
    {
        //
    }
}
