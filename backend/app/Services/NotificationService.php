<?php

namespace App\Services;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\Interfaces\NotificationServiceInterface;
use App\Repositories\Interfaces\NotificationRepositoryInterface;
use App\Models\Notification;
use App\Http\Resources\NotificationResource;

class NotificationService implements NotificationServiceInterface
{
    protected $notificationRepository;
    protected ?int $authorizedUserId;

    public function __construct(
        NotificationRepositoryInterface $notificationRepository,
    ) {
        $this->notificationRepository = $notificationRepository;
        $this->authorizedUserId = Auth::id();
    }

    public function index(): JsonResource
    {
        return NotificationResource::collection(
            $this->notificationRepository->getByUserId($this->authorizedUserId)
        );
    }

    public function seen(Notification $notification): void
    {
        Gate::authorize('update', [Notification::class, $notification]);

        $this->notificationRepository->seen($notification);
    }

    public function delete(Notification $notification): void
    {
        Gate::authorize('delete', [Notification::class, $notification]);

        $this->notificationRepository->delete($notification);
    }
}
