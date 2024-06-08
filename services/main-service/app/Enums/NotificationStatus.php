<?php

namespace App\Enums;

enum NotificationStatus: string
{
    case Unseen = 'unseen';
    case Seen = 'seen';
}
