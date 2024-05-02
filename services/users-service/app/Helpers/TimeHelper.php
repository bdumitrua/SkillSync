<?php

namespace App\Helpers;

use DateTime;

class TimeHelper
{
    public static function getSeconds(int $seconds)
    {
        return now()->addSeconds($seconds);
    }

    public static function getMinutes(int $minutes)
    {
        return now()->addMinutes($minutes);
    }

    public static function getHours(int $hours)
    {
        return now()->addHours($hours);
    }

    public static function calculateAge(string $birthdate): int
    {
        $today = new DateTime(date('Y-m-d'));
        $birthdate = new DateTime($birthdate);
        $age = $today->diff($birthdate)->y;

        return $age;
    }
}
