<?php

namespace App\Observers;

use Illuminate\Support\Facades\Log;
use App\Models\Notification;
use App\Events\NewNotificationEvent;

class NotificationObserver
{
    /**
     * Handle the Notification "created" event.
     */
    public function created(Notification $notification): void
    {
        Log::debug("Handling 'created' method in NotificationObserver", [
            'notification' => $notification->toArray()
        ]);

        event(new NewNotificationEvent($notification));
    }

    /**
     * Handle the Notification "updated" event.
     */
    public function updated(Notification $notification): void
    {
        //
    }

    /**
     * Handle the Notification "deleted" event.
     */
    public function deleted(Notification $notification): void
    {
        //
    }
}
