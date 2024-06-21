<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\Elasticsearchable;
use App\Repositories\User\UserRepository;
use App\Repositories\User\Interfaces\UserRepositoryInterface;

class DialogChat extends Model
{
    use HasFactory, Elasticsearchable;

    protected $fillable = [
        'chat_id',
        'first_user_id',
        'second_user_id'
    ];

    public static function getESIndex(): string
    {
        return 'dialog_chats';
    }

    protected static function getESRefreshInterval(): string
    {
        return '5s';
    }

    public function toSearchableArray(): array
    {
        /** @var UserRepository */
        $userRepository = app(UserRepositoryInterface::class);
        $membersData = $userRepository->getByIds(
            [
                $this->first_user_id,
                $this->second_user_id
            ]
        )->keyBy('id');

        $arrayData = $this->attributesToArray();
        $arrayData['membersData'] = [
            $membersData->get($this->first_user_id)->toSearchableArray(),
            $membersData->get($this->second_user_id)->toSearchableArray(),
        ];

        return $arrayData;
    }

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
            'first_user_id' => [
                'type' => 'integer',
                'index' => false
            ],
            'second_user_id' => [
                'type' => 'integer',
                'index' => false
            ],
            'membersData' => [
                'type' => 'nested',
                'properties' => User::getSearchProperties()
            ],
        ];
    }

    protected static function getSearchQuery(string $searchString): array
    {
        return [
            "nested" => [
                "path" => "membersData",
                "query" => [
                    "bool" => [
                        "must" => [
                            [
                                "bool" => [
                                    "should" => [
                                        [
                                            "multi_match" => [
                                                "query" => $searchString,
                                                "fields" => ["membersData.full_name", "membersData.nick_name"],
                                                "fuzziness" => "AUTO"
                                            ]
                                        ],
                                        [
                                            "match_phrase_prefix" => [
                                                "membersData.full_name" => $searchString
                                            ]
                                        ],
                                        [
                                            "match_phrase_prefix" => [
                                                "membersData.nick_name" => $searchString
                                            ]
                                        ]
                                    ],
                                    "minimum_should_match" => 1
                                ]
                            ]
                        ],
                        "must_not" => [
                            [
                                "term" => [
                                    "membersData.id" => Auth::id()
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function firstMember(): BelongsTo
    {
        return $this->belongsTo(User::class, 'first_user_id', 'id');
    }

    public function secondMember(): BelongsTo
    {
        return $this->belongsTo(User::class, 'second_user_id', 'id');
    }

    public function membersIds(): array
    {
        return [
            $this->first_user_id,
            $this->second_user_id,
        ];
    }
}
