<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\Cacheable;
use App\Repositories\Interfaces\TagRepositoryInterface;
use App\Models\Tag;
use App\DTO\CreateTagDTO;

class TagRepository implements TagRepositoryInterface
{
    use Cacheable;

    public function getByEntityId(int $entityId, string $entityType): Collection
    {
        Log::debug("Getting $entityType tags by id", [
            'entityId' => $entityId
        ]);

        $cacheKey = $this->getTagsCacheKey($entityType, $entityId);
        $cacheTime = $this->getTagsCacheTime($entityType);

        return $this->getCachedData($cacheKey, $cacheTime, function () use ($entityType, $entityId) {
            return Tag::where('entity_type', '=', $entityType)->where('entity_id', '=', $entityId)->get();
        });
    }

    public function getByEntityIds(array $entityIds, string $entityType): Collection
    {
        Log::debug("Getting $entityType's tags by entityIds", [
            'entityIds' => $entityIds
        ]);

        return $this->getCachedCollection($entityIds, function ($entityId, $entityType) {
            return $this->getByEntityId($entityId, $entityType);
        }, $entityType);
    }

    public function create(CreateTagDTO $dto): Tag
    {
        Log::debug('Start create new tag', [
            'dto' => $dto->toArray()
        ]);

        $newTag = Tag::create(
            $dto->toArray()
        );

        Log::debug('Created new tag', [
            'tag' => $newTag->toArray()
        ]);

        return $newTag;
    }

    public function delete(Tag $tag): void
    {
        $tagId = $tag->id;
        Log::debug('Start delete tag', [
            'tagId' => $tag->id
        ]);

        $tag->delete();

        Log::debug('Deleted tag', [
            'tagId' => $tagId
        ]);
    }

    protected function getTagsCacheKey(string $entityType, int $entityId): string
    {
        $cacheKeys = [
            config('entities.user') => CACHE_KEY_USER_TAGS_DATA,
            config('entities.team') => CACHE_KEY_TEAM_TAGS_DATA,
            config('entities.post') => CACHE_KEY_POST_TAGS_DATA,
            config('entities.project') => CACHE_KEY_PROJECT_TAGS_DATA,
        ];

        return $cacheKeys[$entityType] . $entityId;
    }

    protected function getTagsCacheTime(string $entityType): string
    {
        $cacheTime = [
            config('entities.user') => CACHE_TIME_USER_TAGS_DATA,
            config('entities.team') => CACHE_TIME_TEAM_TAGS_DATA,
            config('entities.post') => CACHE_TIME_POST_TAGS_DATA,
            config('entities.project') => CACHE_TIME_PROJECT_TAGS_DATA,
        ];

        return $cacheTime[$entityType];
    }
}
