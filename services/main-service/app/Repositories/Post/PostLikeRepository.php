<?php

namespace App\Repositories\Post;

use App\Models\PostLike;
use App\Repositories\Post\Interfaces\PostLikeRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class PostLikeRepository implements PostLikeRepositoryInterface
{
    protected function queryByBothIds(int $postId, int $userId): Builder
    {
        return PostLike::query()->where('post_id', '=', $postId)->where("user_id", '=', $userId);
    }

    public function getByUserId(int $userId): Collection
    {
        Log::debug("Getting posts likes by userId", [
            'userId' => $userId
        ]);

        return PostLike::where("user_id", '=', $userId)->get();
    }

    public function getByPostId(int $postId): Collection
    {
        Log::debug("Getting posts likes by postId", [
            'postId' => $postId
        ]);

        return PostLike::where("post_id", '=', $postId)->get();
    }

    public function getByBothIds(int $postId, int $userId): ?PostLike
    {
        Log::debug("Getting post like by both ids", [
            'postId' => $postId,
            'userId' => $userId
        ]);

        return $this->queryByBothIds($postId, $userId)->first();
    }

    public function getByUserAndPostsIds(int $userId, array $postsIds): Collection
    {
        Log::debug("Getting posts likes by userId and postsIds", [
            'userId' => $userId,
            'postsIds' => $postsIds,
        ]);

        return PostLike::where('user_id', '=', $userId)->whereIn('post_id', $postsIds)->get();
    }

    public function userLikedPost(int $userId, int $postId): bool
    {
        Log::debug("Checking if user liked post", [
            'userId' => $userId,
            'postId' => $postId
        ]);

        return $this->queryByBothIds($postId, $userId)->exists();
    }

    public function create(int $postId, int $userId): bool
    {
        if ($this->userLikedPost($userId, $postId)) {
            return false;
        }

        PostLike::create([
            'post_id' => $postId,
            'user_id' => $userId,
        ]);

        return true;
    }

    public function delete(int $postId, int $userId): bool
    {
        $like = $this->getByBothIds($postId, $userId);

        if (empty($like)) {
            return false;
        }

        return $like->delete();
    }
}
