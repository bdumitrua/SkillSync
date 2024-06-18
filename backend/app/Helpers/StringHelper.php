<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class StringHelper
{
    public static function camelToSnake(string $string): string
    {
        $pattern = '/([a-z])([A-Z])/';
        $replacement = '$1_$2';
        $snakeCase = strtolower(preg_replace($pattern, $replacement, $string));

        return $snakeCase;
    }

    public static function snakeToCamel(string $string): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $string))));
    }

    public static function generateMessageUuid(int $chatId, int $senderId): string
    {
        // ChatId string
        $cis = "ci" . (string)$chatId;
        // SenderId string
        $sis = "si" . (string)$senderId . 'uu';

        $baseUuid = Str::uuid();

        return $cis . $sis . $baseUuid;
    }

    public static function decodeMessageUuid(string $messageUuid): array
    {
        $ciStr = 'ci';
        $ciLen = strlen($ciStr);
        $ciPos = strpos($messageUuid, $ciStr);

        $siStr = 'si';
        $siLen = strlen($siStr);
        $siPos = strpos($messageUuid, $siStr);

        $uuStr = 'uu';
        $uuPos = strpos($messageUuid, $uuStr);

        // Извлечение chatId
        $chatId = substr($messageUuid, $ciPos + $ciLen, $siPos - ($ciPos + $ciLen));

        // Извлечение senderId
        $senderId = substr($messageUuid, $siPos + $siLen, $uuPos - ($siPos + $siLen));

        return [
            'chatId' => (int)$chatId,
            'senderId' => (int)$senderId
        ];
    }
}
