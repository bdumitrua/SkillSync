<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\User;
use App\Models\Notification;

class NotificationPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Notification $notification): Response
    {
        return $user->id === $notification->receiver_id
            ? Response::allow()
            : Response::deny("Access denied.");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Notification $notification): Response
    {
        return $user->id === $notification->receiver_id
            ? Response::allow()
            : Response::deny("Access denied.");
    }
}
