<?php

namespace App\Repositories\Post\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use App\Models\PostLike;
use App\Models\Post;

interface PostLikeRepositoryInterface
{
    /**
     * @param int $userId
     * 
     * @return Collection
     */
    public function getByUserId(int $userId): Collection;

    /**
     * @param int $postId
     * 
     * @return Collection
     */
    public function getByPostId(int $postId): Collection;

    /**
     * @param int $postId
     * @param int $userId
     * 
     * @return PostLike|null
     */
    public function getByBothIds(int $postId, int $userId): ?PostLike;

    /**
     * @param int $userId
     * @param array $postsIds
     * 
     * @return Collection
     */
    public function getByUserAndPostsIds(int $userId, array $postsIds): Collection;

    /**
     * @param int $userId
     * @param int $postId
     * 
     * @return bool
     */
    public function userLikedPost(int $userId, int $postId): bool;

    /**
     * @param Post $post
     * @param int $userId
     * 
     * @return bool
     */
    public function create(Post $post, int $userId): bool;

    /**
     * @param Post $post
     * @param int $userId
     * 
     * @return bool
     */
    public function delete(Post $post, int $userId): bool;
}
