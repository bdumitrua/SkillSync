<?php

use Illuminate\Support\Facades\Broadcast;

// Broadcast Channels
Broadcast::channel('user.notifications.{receiverId}', function ($user, $receiverId) {
    return $user->id === $receiverId;
}, ['guards' => ['api']]);
