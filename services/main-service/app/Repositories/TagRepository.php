<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\TagRepositoryInterface;
use App\Models\Tag;
use App\DTO\User\CreateTagDTO;
use App\Traits\GetCachedData;
use Illuminate\Database\Eloquent\Builder;

class TagRepository implements TagRepositoryInterface
{
    use GetCachedData;

    protected $cacheKeys;

    public function __construct()
    {
        $this->cacheKeys = [
            config('entities.user') => CACHE_KEY_USER_TAGS_DATA,
            config('entities.team') => CACHE_KEY_TEAM_TAGS_DATA,
            config('entities.post') => CACHE_KEY_POST_TAGS_DATA,
        ];
    }


    protected function queryByUser(): Builder
    {
        return Tag::query()->where('entity_type', '=', config('entities.user'));
    }

    protected function queryByTeam(): Builder
    {
        return Tag::query()->where('entity_type', '=', config('entities.team'));
    }

    protected function queryByPost(): Builder
    {
        return Tag::query()->where('entity_type', '=', config('entities.post'));
    }

    public function getByUserId(int $userId): Collection
    {
        $cacheKey = $this->getTagsCacheKey(config('entities.user'), $userId);
        return $this->getCachedData($cacheKey, CACHE_TIME_USER_TAGS_DATA, function () use ($userId) {
            return $this->queryByUser()->where('entity_id', '=', $userId)->get();
        });
    }

    public function getByUserIds(array $userIds): Collection
    {
        return $this->getCachedCollection($userIds, function ($userId) {
            return $this->getByUserId($userId);
        });
    }

    public function getByTeamId(int $teamId): Collection
    {
        $cacheKey = $this->getTagsCacheKey(config('entities.team'), $teamId);
        return $this->getCachedData($cacheKey, CACHE_TIME_TEAM_TAGS_DATA, function () use ($teamId) {
            return $this->queryByTeam()->where('entity_id', '=', $teamId)->get();
        });
    }

    public function getByTeamIds(array $teamIds): Collection
    {
        return $this->getCachedCollection($teamIds, function ($teamId) {
            return $this->getByTeamId($teamId);
        });
    }

    public function getByPostId(int $postId): Collection
    {
        $cacheKey = $this->getTagsCacheKey(config('entities.post'), $postId);
        return $this->getCachedData($cacheKey, CACHE_TIME_POST_TAGS_DATA, function () use ($postId) {
            return $this->queryByPost()->where('entity_id', '=', $postId)->get();
        });
    }

    public function getByPostIds(array $postIds): Collection
    {
        return $this->getCachedCollection($postIds, function ($postId) {
            return $this->getByPostId($postId);
        });
    }

    public function create(CreateTagDTO $dto): Tag
    {
        $newTag = Tag::create(
            $dto->toArray()
        );

        return $newTag;
    }

    public function delete(Tag $tag): void
    {
        $entityType = $tag->entity_type;
        $entityId = $tag->entity_id;

        $tag->delete();
        $this->clearTagsCache($entityType, $entityId);
    }

    protected function getTagsCacheKey(string $entityType, int $entityId): string
    {
        return $this->cacheKeys[$entityType] . $entityId;
    }

    protected function clearTagsCache(string $entityType, int $entityId): void
    {
        $this->clearCache($this->getTagsCacheKey($entityType, $entityId));
    }
}
