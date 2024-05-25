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
}
