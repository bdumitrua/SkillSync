<?php

namespace App\Observers;

use App\Traits\Cacheable;
use App\Models\Tag;

class TagObserver
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

    /**
     * Handle the Tag "created" event.
     */
    public function created(Tag $tag): void
    {
        $this->clearMainCache($tag->entity_type, $tag->entity_id);
    }

    /**
     * Handle the Tag "updated" event.
     */
    public function updated(Tag $tag): void
    {
        $this->clearMainCache($tag->entity_type, $tag->entity_id);
    }

    /**
     * Handle the Tag "deleted" event.
     */
    public function deleted(Tag $tag): void
    {
        $this->clearMainCache($tag->entity_type, $tag->entity_id);
    }

    /**
     * * Recache stuff
     */

    protected function getMainCacheKey(string $entityType, int $entityId): string
    {
        return $this->cacheKeys[$entityType] . $entityId;
    }

    protected function clearMainCache(string $entityType, int $entityId): void
    {
        $this->clearCache($this->getMainCacheKey($entityType, $entityId));
    }
}
