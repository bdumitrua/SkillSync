<?php

namespace App\Models;

use Elastic\Elasticsearch\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Laravel\Scout\Searchable;

class Team extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'name',
        'avatar',
        'description',
        'email',
        'site',
        'chat_id',
        'admin_id'
    ];

    public static function createElasticsearchIndex()
    {
        $client = app(Client::class);

        if ($client->indices()->exists(['index' => 'teams'])->asBool()) {
            $client->indices()->delete(['index' => 'teams']);
        }

        $params = [
            'index' => 'teams',
            'body' => [
                'mappings' => [
                    'properties' => [
                        'id' => [
                            'type' => 'keyword'
                        ],
                        'name' => [
                            'type' => 'text'
                        ],
                        'avatar' => [
                            'type' => 'text',
                            'index' => false
                        ],
                        'description' => [
                            'type' => 'text',
                            'index' => false
                        ],
                        'email' => [
                            'type' => 'text',
                            'index' => false
                        ],
                        'site' => [
                            'type' => 'text',
                            'index' => false
                        ],
                        'chat_id' => [
                            'type' => 'text',
                            'index' => false
                        ],
                        'admin_id' => [
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

        if ($client->indices()->exists(['index' => 'teams'])->asBool()) {
            $client->indices()->delete(['index' => 'teams']);
        }
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'avatar' => $this->avatar,
            'description' => $this->description,
            'email' => $this->email,
            'site' => $this->site,
            'chat_id' => $this->chat_id,
            'admin_id' => $this->admin_id,
        ];
    }

    public static function search($query): Collection
    {
        $client = app(Client::class);

        $params = [
            'index' => 'teams',
            'body' => [
                'query' => [
                    'multi_match' => [
                        'query' => $query,
                        'fields' => ['name'],  // Поиск только по индексируемым полям
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
            $results[] = new Team($hit['_source']);
        }

        return new Collection($results);
    }

    /**
     * @return BelongsTo
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * @return HasMany
     */
    public function members(): HasMany
    {
        return $this->hasMany(TeamMember::class);
    }

    /**
     * @return HasMany
     */
    public function links(): HasMany
    {
        return $this->hasMany(TeamLink::class);
    }

    /**
     * @return HasMany
     */
    public function vacancies(): HasMany
    {
        return $this->hasMany(TeamVacancy::class);
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

    /**
     * @return MorphMany
     */
    public function subscribers(): MorphMany
    {
        return $this->morphMany(Subscription::class, 'entity');
    }
}
