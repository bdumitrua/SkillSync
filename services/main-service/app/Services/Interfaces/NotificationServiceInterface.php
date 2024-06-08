<?php

namespace App\Services\Interfaces;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Notification;

interface NotificationServiceInterface
{
    /**
     * @return JsonResource
     */
    public function index(): JsonResource;

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
