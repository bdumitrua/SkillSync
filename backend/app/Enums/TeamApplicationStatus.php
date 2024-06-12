<?php

namespace App\Enums;

enum TeamApplicationStatus: string
{
    case Sended = 'sended';
    case Readed = 'readed';
    case Accepted = 'accepted';
    case Rejected = 'rejected';
}
