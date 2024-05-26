<?php

namespace App\Repositories\Post;

use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Post\Interfaces\PostCommentRepositoryInterface;
use App\Models\PostComment;
use App\DTO\Post\CreatePostCommentDTO;

class PostCommentRepository implements PostCommentRepositoryInterface
{
    public function getById(int $postCommentId): ?PostComment
    {
        return PostComment::where('id', '=', $postCommentId)->first();
    }

    public function getByPostId(int $postId): Collection
    {
        return PostComment::where('post_id', '=', $postId)->get();
    }

    public function create(CreatePostCommentDTO $dto): PostComment
    {
        $newComment = PostComment::create(
            $dto->toArray()
        );

        return $newComment;
    }

    public function delete(PostComment $postComment): void
    {
        $postComment->delete();
    }
}
