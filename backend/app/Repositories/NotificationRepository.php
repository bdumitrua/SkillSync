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
        Notification::create(
            $dto->toArray()
        );
    }

    public function seen(Notification $notification): void
    {
        $notification->status = NotificationStatus::Seen;
        $notification->save();
    }

    public function delete(Notification $notification): void
    {
        $notification->delete();
    }

    protected function getNotificationsCacheKey(int $userId): string
    {
        return CACHE_KEY_USER_NOTIFICATIONS_DATA . $userId;
    }
}
