<?php

namespace App\Models;

use App\Prometheus\PrometheusServiceProxy;
use Elastic\Elasticsearch\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * * Модель, относящаяся к таблице users
 * 
 * * Необходима для работы с основными данными пользователей и выстраивания связей.
 * 
 * * Также, соответственно, отвечает за аутентификацию пользователей и валидацию JWT токенов.
 */
class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, Searchable, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'nick_name',
        'email',
        'password',
        'phone',
        'about',
        'avatar',
        'address',
        'birthdate',
        'gender',
        'token_invalid_before'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'token_invalid_before' => 'datetime',
    ];

    public static function createElasticsearchIndex()
    {
        $client = app(Client::class);

        if ($client->indices()->exists(['index' => 'users'])->asBool()) {
            $client->indices()->delete(['index' => 'users']);
        }

        $params = [
            'index' => 'users',
            'body' => [
                'mappings' => [
                    'properties' => [
                        'id' => [
                            'type' => 'keyword'
                        ],
                        'first_name' => [
                            'type' => 'text',
                            'index' => false
                        ],
                        'last_name' => [
                            'type' => 'text',
                            'index' => false
                        ],
                        'full_name' => [
                            'type' => 'text'
                        ],
                        'nick_name' => [
                            'type' => 'text'
                        ],
                        'email' => [
                            'type' => 'text',
                            'index' => false  // не индексируется
                        ],
                        'phone' => [
                            'type' => 'text',
                            'index' => false
                        ],
                        'about' => [
                            'type' => 'text',
                            'index' => false
                        ],
                        'avatar' => [
                            'type' => 'text',
                            'index' => false
                        ],
                        'address' => [
                            'type' => 'text',
                            'index' => false
                        ],
                        'birthdate' => [
                            'type' => 'text',
                            'index' => false
                        ],
                        'gender' => [
                            'type' => 'text',
                            'index' => false
                        ],
                    ]
                ]
            ]
        ];

        $response = $client->indices()->create($params);
        return $response;
    }

    public static function deleteElasticsearchIndex()
    {
        $client = app(Client::class);

        if ($client->indices()->exists(['index' => 'users'])->asBool()) {
            $client->indices()->delete(['index' => 'users']);
        }
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            // Добавляем объединенное поле чтобы искать по нему
            'full_name' => $this->first_name . ' ' . $this->last_name,
            'nick_name' => $this->nick_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'about' => $this->about,
            'avatar' => $this->avatar,
            'address' => $this->address,
            'birthdate' => $this->birthdate,
            'gender' => $this->gender,
        ];
    }

    public static function search($query): Collection
    {
        $client = app(Client::class);

        $params = [
            'index' => 'users',
            'body' => [
                'query' => [
                    'multi_match' => [
                        'query' => $query,
                        'fields' => ['full_name', 'nick_name'],  // Поиск только по индексируемым полям
                        "fuzziness" => 2
                    ]
                ]
            ]
        ];

        $response = $client->search($params);

        // Обработка результатов поиска
        $hits = $response['hits']['hits'];

        $results = [];
        foreach ($hits as $hit) {
            $results[] = new User($hit['_source']);
        }

        return new Collection($results);
    }

    protected static function boot(): void
    {
        parent::boot();

        static::created(function ($user) {
            app(PrometheusServiceProxy::class)->incrementEntityCreatedCount('User');
        });
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'userId' => $this->id,
            'email' => $this->email
        ];
    }

    /**
     * @return MorphMany
     */
    public function subscribers(): MorphMany
    {
        return $this->morphMany(Subscription::class, 'entity');
    }

    /**
     * @return hasMany
     */
    public function subscriptions(): hasMany
    {
        return $this->hasMany(Subscription::class, 'user_id', 'id');
    }

    /**
     * @return MorphMany
     */
    public function posts(): MorphMany
    {
        return $this->morphMany(Post::class, 'entity');
    }

    /**
     * @return MorphMany
     */
    public function tags(): MorphMany
    {
        return $this->morphMany(Tag::class, 'entity');
    }
}
