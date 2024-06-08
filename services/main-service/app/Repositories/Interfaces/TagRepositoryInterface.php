<?php

namespace App\Repositories\Interfaces;

use App\DTO\User\CreateTagDTO;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;

interface TagRepositoryInterface
{
    /**
     * @param int $userId
     * 
     * @return Collection
     */
    public function getByUserId(int $userId): Collection;

    /**
     * @param array $userIds
     * 
     * @return array
     */
    public function getByUserIds(array $userIds): array;

    /**
     * @param int $teamId
     * 
     * @return Collection
     */
    public function getByTeamId(int $teamId): Collection;

    /**
     * @param array $teamIds
     * 
     * @return array
     */
    public function getByTeamIds(array $teamIds): array;

    /**
     * @param int $postId
     * 
     * @return Collection
     */
    public function getByPostId(int $postId): Collection;

    /**
     * @param array $postIds
     * 
     * @return array
     */
    public function getByPostIds(array $postIds): array;

    /**
     * @param CreateTagDTO $dto
     * 
     * @return Tag
     */
    public function create(CreateTagDTO $dto): Tag;

    /**
     * @param Tag $tag
     * 
     * @return void
     */
    public function delete(Tag $tag): void;
}
