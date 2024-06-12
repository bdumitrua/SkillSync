<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Notification;
use App\DTO\User\CreateNotificationDTO;

interface NotificationRepositoryInterface
{
    /**
     * @param int $userId
     * 
     * @return Collection
     */
    public function getByUserId(int $userId): Collection;

    /**
     * @param CreateNotificationDTO $dto
     * 
     * @return void
     */
    public function create(CreateNotificationDTO $dto): void;

    /**
     * @param Notification $notification
     * 
     * @return void
     */
    public function seen(Notification $notification): void;

    /**
     * @param Notification $notification
     * 
     * @return void
     */
    public function delete(Notification $notification): void;
}
