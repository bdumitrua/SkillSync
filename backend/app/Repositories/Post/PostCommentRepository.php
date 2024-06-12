<?php

namespace App\Repositories\Post;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Post\Interfaces\PostCommentRepositoryInterface;
use App\Models\PostComment;
use App\DTO\Post\CreatePostCommentDTO;

class PostCommentRepository implements PostCommentRepositoryInterface
{
    public function getById(int $postCommentId): ?PostComment
    {
        Log::debug("Getting post comment by id", [
            'postCommentId' => $postCommentId
        ]);

        return PostComment::where('id', '=', $postCommentId)->first();
    }

    public function getByPostId(int $postId): Collection
    {
        Log::debug("Getting post comments by postId", [
            'postId' => $postId
        ]);

        return PostComment::where('post_id', '=', $postId)->get();
    }

    public function create(CreatePostCommentDTO $dto): PostComment
    {
        Log::debug('Creating post comment from dto', [
            'dto' => $dto->toArray()
        ]);

        $newComment = PostComment::create(
            $dto->toArray()
        );

        Log::debug('Succesfully created post comment from dto', [
            'dto' => $dto->toArray(),
            'newComment' => $newComment->toArray()
        ]);

        return $newComment;
    }

    public function delete(PostComment $postComment): void
    {
        $postCommentData = $postComment->toArray();

        Log::debug('Deleting post comment', [
            'postCommentData' => $postCommentData,
        ]);

        $postComment->delete();

        Log::debug('Succesfully deleted post comment', [
            'postCommentData' => $postCommentData,
        ]);
    }
}
