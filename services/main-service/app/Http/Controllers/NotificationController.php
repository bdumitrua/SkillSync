<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Interfaces\NotificationServiceInterface;
use App\Models\Notification;

class NotificationController extends Controller
{
    private $notificationService;

    public function __construct(NotificationServiceInterface $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        return $this->handleServiceCall(function () {
            return $this->notificationService->index();
        });
    }

    public function seen(Notification $notification)
    {
        return $this->handleServiceCall(function () use ($notification) {
            return $this->notificationService->seen($notification);
        });
    }

    public function delete(Notification $notification)
    {
        return $this->handleServiceCall(function () use ($notification) {
            return $this->notificationService->delete($notification);
        });
    }
}
