<?php

namespace App\Enums;

enum NotificationType: string
{
    case PostLike = 'postLike';
    case CommentLike = 'commentLike';
    case Subscription = 'subscription';
}
