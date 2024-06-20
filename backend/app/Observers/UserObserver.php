<?php

namespace App\Observers;

use App\Traits\Cacheable;
use App\Models\User;

class UserObserver
{
    use Cacheable;

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $this->clearAllCache($user->id);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        $this->clearMainCache($user->id);
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $this->clearAllCache($user->id);
    }

    /**
     * * Recache stuff
     */

    protected function getMainCacheKey(int $userId): string
    {
        return CACHE_KEY_USER_DATA . $userId;
    }

    protected function clearMainCache(int $userId): void
    {
        $this->clearCache($this->getMainCacheKey($userId));
    }

    protected function getCacheKeys(int $userId): array
    {
        return [
            CACHE_KEY_USER_DATA . $userId,
            CACHE_KEY_USER_NOTIFICATIONS_DATA . $userId,
            CACHE_KEY_USER_TAGS_DATA . $userId,
        ];
    }

    protected function clearAllCache(int $userId): void
    {
        $cacheKeys = $this->getCacheKeys($userId);

        foreach ($cacheKeys as $cacheKey) {
            $this->clearCache($cacheKey);
        }
    }
}
