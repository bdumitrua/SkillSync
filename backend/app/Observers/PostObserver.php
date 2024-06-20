<?php

namespace App\Observers;

use App\Traits\Cacheable;
use App\Models\Post;

class PostObserver
{
    use Cacheable;

    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        $this->clearAllCache($post->id);
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        // 
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        $this->clearAllCache($post->id);
    }

    /**
     * * Recache stuff
     */

    protected function getCacheKeys(int $postId): array
    {
        return [
            CACHE_KEY_POST_TAGS_DATA . $postId,
        ];
    }

    protected function clearAllCache(int $postId): void
    {
        $cacheKeys = $this->getCacheKeys($postId);

        foreach ($cacheKeys as $cacheKey) {
            $this->clearCache($cacheKey);
        }
    }
}
