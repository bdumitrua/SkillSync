<?php

namespace App\Repositories;

use App\Repositories\Interfaces\TagRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TagRepository implements TagRepositoryInterface
{
    public function getByUserId(int $userId): Collection
    {
        return new Collection();
    }

    public function getByUserIds(array $userIds): Collection
    {
        return new Collection();
    }

    public function getByTeamId(int $teamId): Collection
    {
        return new Collection();
    }

    public function getByTeamIds(array $teamIds): Collection
    {
        return new Collection();
    }

    public function getByPostId(int $postId): Collection
    {
        return new Collection();
    }

    public function getByPostIds(array $postIds): Collection
    {
        return new Collection();
    }
}
