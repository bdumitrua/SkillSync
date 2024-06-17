<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\Cacheable;
use App\Repositories\Interfaces\NotificationRepositoryInterface;
use App\Models\Notification;
use App\Enums\NotificationStatus;
use App\DTO\CreateNotificationDTO;

class NotificationRepository implements NotificationRepositoryInterface
{
    use Cacheable;

    public function getByUserId(int $userId): Collection
    {
        Log::debug('Getting notifications by userId', [
            'userId' => $userId
        ]);

        $cacheKey = $this->getNotificationsCacheKey($userId);
        return $this->getCachedData($cacheKey, CACHE_TIME_USER_NOTIFICATIONS_DATA, function () use ($userId) {
            return Notification::where('receiver_id', '=', $userId)->get();
        });
    }

    public function create(CreateNotificationDTO $dto): void
    {
        $newNotification = Notification::create(
            $dto->toArray()
        );

        $this->clearNotificationsCache($newNotification->receiver_id);
    }

    public function seen(Notification $notification): void
    {
        $notification->status = NotificationStatus::Seen;
        $notification->save();

        $this->clearNotificationsCache($notification->receiver_id);
    }

    public function delete(Notification $notification): void
    {
        $notificationReceiverId = $notification->receiver_id;

        $notification->delete();
        $this->clearNotificationsCache($notificationReceiverId);
    }

    protected function getNotificationsCacheKey(int $userId): string
    {
        return CACHE_KEY_USER_NOTIFICATIONS_DATA . $userId;
    }

    protected function clearNotificationsCache(int $userId): void
    {
        $this->clearCache($this->getNotificationsCacheKey($userId));
    }
}
