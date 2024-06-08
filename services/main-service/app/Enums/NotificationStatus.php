<?php

namespace App\Enums;

enum NotificationStatus: string
{
    case Unseed = 'unseen';
    case Seen = 'seen';
}
