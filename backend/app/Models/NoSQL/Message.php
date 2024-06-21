<?php

namespace App\Models\NoSQL;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\Elasticsearchable;
use App\Enums\MessageStatus;
use App\DTO\Message\MessageDTO;
use App\DTO\Message\CreateMesssageDTO;

class Message
{
    use Elasticsearchable;

    public string $uuid;
    public int $chatId;
    public string $text;
    public string $status;
    public int $senderId;
    public $created_at;

    public function __construct(string $uuid, int $chatId, string $text, string $status, int $senderId, $created_at)
    {
        $this->uuid = $uuid;
        $this->chatId = $chatId;
        $this->text = $text;
        $this->status = $status;
        $this->senderId = $senderId;
        $this->created_at = $created_at;
    }

    public function attributesToArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'chatId' => $this->chatId,
            'text' => $this->text,
            'status' => $this->status,
            'senderId' => $this->senderId,
            'created_at' => $this->created_at,
        ];
    }

    protected static function getESIndex(): string
    {
        return 'messages';
    }

    protected static function getESRefreshInterval(): string
    {
        return '1s';
    }

    public function toSearchableArray(): array
    {
        return $this->attributesToArray();
    }

    protected static function getSearchProperties(): array
    {
        return  [
            'uuid' => [
                'type' => 'keyword'
            ],
            'chatId' => [
                'type' => 'integer',
                'index' => false
            ],
            'text' => [
                'type' => 'text'
            ],
            'status' => [
                'type' => 'text',
                'index' => false
            ],
            'senderId' => [
                'type' => 'integer',
                'index' => false
            ],
            'created_at' => [
                'type' => 'date',
                'index' => false,
            ],
        ];
    }

    protected static function getSearchQuery(string $searchString): array
    {
        return [
            'bool' => [
                'should' => [
                    [
                        'multi_match' => [
                            'query' => $searchString,
                            'fields' => ['text'],
                            'fuzziness' => 'AUTO'
                        ]
                    ],
                    [
                        'match_phrase_prefix' => [
                            'text' => [
                                'query' => $searchString
                            ]
                        ]
                    ]
                ],
                'minimum_should_match' => 1
            ],
        ];
    }

    public static function readElasticsearchMessage($messageUuid): void
    {
        $client = static::getElasticsearchClient();

        $params = [
            'index' => static::getESIndex(),
            'id' => $messageUuid,
            'body' => [
                'doc' => [
                    'status' => MessageStatus::Readed->value
                ]
            ]
        ];

        $client->update($params);
    }

    protected static function performElasticsearchSearch(array $response): Collection
    {
        Log::debug('Performing ES search for Message', [
            'response' => $response
        ]);

        // Process search results
        $hits = $response['hits']['hits'];

        $results = [];
        foreach ($hits as $hit) {
            $model = new self(
                $hit['_source']['uuid'],
                $hit['_source']['chatId'],
                $hit['_source']['text'],
                $hit['_source']['status'],
                $hit['_source']['senderId'],
                $hit['_source']['created_at'],
            );

            $results[] = $model;
        }

        return new Collection($results);
    }
}
