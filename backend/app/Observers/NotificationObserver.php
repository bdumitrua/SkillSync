<?php

namespace App\Observers;

use Illuminate\Support\Facades\Log;
use App\Traits\Cacheable;
use App\Models\Notification;
use App\Events\NewNotificationEvent;

class NotificationObserver
{
    use Cacheable;

    /**
     * Handle the Notification "created" event.
     */
    public function created(Notification $notification): void
    {
        $this->clearAllCache($notification->receiver_id);

        event(new NewNotificationEvent($notification));
    }

    /**
     * Handle the Notification "updated" event.
     */
    public function updated(Notification $notification): void
    {
        $this->clearAllCache($notification->receiver_id);
    }

    /**
     * Handle the Notification "deleted" event.
     */
    public function deleted(Notification $notification): void
    {
        $this->clearAllCache($notification->receiver_id);
    }

    /**
     * * Recache stuff
     */

    //  At the moment it's only CACHE_KEY_USER_NOTIFICATIONS_DATA, 
    //  but CACHE_KEY_USER_NOTIFICATIONS_DATA isn't MAIN ENTITY, so 'All'
    protected function getCacheKeys(int $receiverId): array
    {
        return [
            CACHE_KEY_USER_NOTIFICATIONS_DATA . $receiverId
        ];
    }

    protected function clearAllCache(int $receiverId): void
    {
        $cacheKeys = $this->getCacheKeys($receiverId);

        foreach ($cacheKeys as $cacheKey) {
            $this->clearCache($cacheKey);
        }
    }
}
