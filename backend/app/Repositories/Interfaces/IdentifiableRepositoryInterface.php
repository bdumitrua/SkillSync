<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface IdentifiableRepositoryInterface
{
    public function getById(int $id);

    public function getByIds(array $ids): Collection;
}
