<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\NotificationRepositoryInterface;
use App\Models\Notification;
use App\DTO\User\CreateNotificationDTO;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function getByUserId(int $userId): Collection
    {
        return new Collection();
    }

    public function create(CreateNotificationDTO $dto): void
    {
        //
    }

    public function seen(Notification $notification): void
    {
        //
    }

    public function delete(Notification $notification): void
    {
        //
    }
}
