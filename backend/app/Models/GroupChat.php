<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Elasticsearchable;

class GroupChat extends Model
{
    use HasFactory, Elasticsearchable;

    protected $fillable = [
        'chat_id',
        'admin_type',
        'admin_id',
        'name',
        'avatar_url'
    ];

    public static function getESIndex(): string
    {
        return 'group_chats';
    }

    protected static function getESRefreshInterval(): string
    {
        return '5s';
    }

    public function toSearchableArray(): array
    {
        return $this->attributesToArray();
    }

    /**
     * Define the properties for the Elasticsearch index mappings.
     *
     * @return array
     */
    protected static function getSearchProperties(): array
    {
        return  [
            'id' => [
                'type' => 'keyword'
            ],
            'chat_id' => [
                'type' => 'integer',
                'index' => false,
            ],
            'admin_type' => [
                'type' => 'text',
                'index' => false
            ],
            'admin_id' => [
                'type' => 'integer',
                'index' => false
            ],
            'name' => [
                'type' => 'text',
            ],
            'avatar_url' => [
                'type' => 'text',
                'index' => false
            ],
            'created_at' => [
                'type' => 'date',
                'index' => false,
            ],
            'updated_at' => [
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
                            'fields' => ['name'],
                            'fuzziness' => 'AUTO'
                        ]
                    ],
                    [
                        'match_phrase_prefix' => [
                            'name' => [
                                'query' => $searchString
                            ]
                        ]
                    ]
                ],
                'minimum_should_match' => 1
            ]
        ];
    }

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function admin(): MorphTo
    {
        return $this->morphTo();
    }

    public function isTeamChat(): bool
    {
        return $this->admin_type === config('entities.team');
    }

    public function isUserGroupChat(): bool
    {
        return $this->admin_type === config('entities.user');
    }

    public function createdByUser(int $userId): bool
    {
        return $this->isUserGroupChat() && $this->admin_id === $userId;
    }
}
