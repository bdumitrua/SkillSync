<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\Cacheable;
use App\Repositories\Interfaces\TagRepositoryInterface;
use App\Models\Tag;
use App\DTO\User\CreateTagDTO;

class TagRepository implements TagRepositoryInterface
{
    use Cacheable;

    protected $cacheKeys;

    public function __construct()
    {
        $this->cacheKeys = [
            config('entities.user') => CACHE_KEY_USER_TAGS_DATA,
            config('entities.team') => CACHE_KEY_TEAM_TAGS_DATA,
            config('entities.post') => CACHE_KEY_POST_TAGS_DATA,
        ];
    }

    public function getByUserId(int $userId): Collection
    {
        Log::debug('Getting tags by userId', [
            'userId' => $userId
        ]);

        $cacheKey = $this->getTagsCacheKey(config('entities.user'), $userId);
        return $this->getCachedData($cacheKey, CACHE_TIME_USER_TAGS_DATA, function () use ($userId) {
            return Tag::where('entity_type', '=', config('entities.user'))->where('entity_id', '=', $userId)->get();
        });
    }

    public function getByUserIds(array $userIds): array
    {
        Log::debug('Getting tags by userIds', [
            'userIds' => $userIds
        ]);

        return $this->getCachedArray($userIds, function ($userId) {
            return $this->getByUserId($userId);
        });
    }

    public function getByTeamId(int $teamId): Collection
    {
        Log::debug('Getting tags by teamId', [
            'teamId' => $teamId
        ]);

        $cacheKey = $this->getTagsCacheKey(config('entities.team'), $teamId);
        return $this->getCachedData($cacheKey, CACHE_TIME_TEAM_TAGS_DATA, function () use ($teamId) {
            return Tag::where('entity_type', '=', config('entities.team'))->where('entity_id', '=', $teamId)->get();
        });
    }

    public function getByTeamIds(array $teamIds): array
    {
        Log::debug('Getting tags by teamIds', [
            'teamIds' => $teamIds
        ]);

        return $this->getCachedArray($teamIds, function ($teamId) {
            return $this->getByTeamId($teamId);
        });
    }

    public function getByPostId(int $postId): Collection
    {
        Log::debug('Getting tags by postId', [
            'postId' => $postId
        ]);

        $cacheKey = $this->getTagsCacheKey(config('entities.post'), $postId);
        return $this->getCachedData($cacheKey, CACHE_TIME_POST_TAGS_DATA, function () use ($postId) {
            return Tag::where('entity_type', '=', config('entities.post'))->where('entity_id', '=', $postId)->get();
        });
    }

    public function getByPostIds(array $postIds): array
    {
        Log::debug('Getting tags by postIds', [
            'postIds' => $postIds
        ]);

        return $this->getCachedArray($postIds, function ($postId) {
            return $this->getByPostId($postId);
        });
    }

    public function create(CreateTagDTO $dto): Tag
    {
        Log::debug('Start create new tag', [
            'dto' => $dto->toArray()
        ]);

        $newTag = Tag::create(
            $dto->toArray()
        );

        $this->clearTagsCache($newTag->entity_type, $newTag->entity_id);

        Log::debug('Created new tag', [
            'tag' => $newTag->toArray()
        ]);

        return $newTag;
    }

    public function delete(Tag $tag): void
    {
        Log::debug('Start delete tag', [
            'tag' => $tag->toArray()
        ]);

        $tagId = $tag->id;
        $entityType = $tag->entity_type;
        $entityId = $tag->entity_id;

        $tag->delete();
        $this->clearTagsCache($entityType, $entityId);

        Log::debug('Deleted tag', [
            'tag' => ['id' => $tagId]
        ]);
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
