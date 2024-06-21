<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;
use App\Models\Team;
use App\Models\Post;
use App\Models\Like;
use App\Models\Interfaces\Likeable;
use App\DTO\LikeDTO;

interface LikeRepositoryInterface
{
    /**
     * @param User $user
     * 
     * @return Collection
     */
    public function getByUser(User $user): Collection;

    /**
     * @param Post $post
     * 
     * @return Collection
     */
    public function getByPost(Post $post): Collection;

    /**
     * @param Collection $likes
     * 
     * @return Collection
     */
    public function getLikesWithLikeable(Collection $likes): Collection;

    /**
     * @param LikeDTO $likeDTO
     * 
     * @return Like|null
     */
    public function getByDTO(LikeDTO $likeDTO): ?Like;

    /**
     * @param LikeDTO $likeDTO
     * 
     * @return Likeable|null
     */
    public function getLikeableByDTO(LikeDTO $likeDTO): ?Likeable;

    /**
     * @param int $userId
     * @param string $entityType
     * @param array $entityIds
     * 
     * @return Collection
     */
    public function getByUserAndEntityIds(int $userId, string $entityType, array $entityIds): Collection;

    /**
     * @param int $userId
     * @param Likeable $likeableModel
     * 
     * @return void
     */
    public function create(int $userId, Likeable $likeableModel): void;

    /**
     * @param Like $like
     * 
     * @return void
     */
    public function delete(Like $like): void;
}
