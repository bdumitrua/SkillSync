<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Tag;
use App\DTO\CreateTagDTO;

interface TagRepositoryInterface
{
    /**
     * @param int $entityId
     * @param string $entityType
     * 
     * @return Collection
     */
    public function getByEntityId(int $entityId, string $entityType): Collection;

    /**
     * @param array $entityIds
     * @param string $entityType
     * 
     * @return Collection
     */
    public function getByEntityIds(array $entityIds, string $entityType): Collection;

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
