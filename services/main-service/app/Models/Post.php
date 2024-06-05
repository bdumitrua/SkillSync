<?php

namespace App\Models;

use Elastic\Elasticsearch\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'text',
        'media_url',
        'entity_type',
        'entity_id',
    ];

    public static function createElasticsearchIndex()
    {
        $client = app(Client::class);

        if ($client->indices()->exists(['index' => 'posts'])->asBool()) {
            $client->indices()->delete(['index' => 'posts']);
        }

        $params = [
            'index' => 'posts',
            'body' => [
                'mappings' => [
                    'properties' => [
                        'id' => [
                            'type' => 'keyword'
                        ],
                        'text' => [
                            'type' => 'text'
                        ],
                        'media_url' => [
                            'type' => 'text',
                            'index' => false
                        ],
                        'entity_type' => [
                            'type' => 'text',
                            'index' => false
                        ],
                        'entity_id' => [
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

        if ($client->indices()->exists(['index' => 'posts'])->asBool()) {
            $client->indices()->delete(['index' => 'posts']);
        }
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'media_url' => $this->media_url,
            'entity_type' => $this->entity_type,
            'entity_id' => $this->entity_id,
        ];
    }

    public static function search($query): Collection
    {
        $client = app(Client::class);

        $params = [
            'index' => 'posts',
            'body' => [
                'query' => [
                    'multi_match' => [
                        'query' => $query,
                        'fields' => ['text'],  // Поиск только по индексируемым полям
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
            $post = new Post($hit['_source']);
            $post->id = $hit['_source']['id'];

            $results[] = $post;
        }

        return new Collection($results);
    }

    /**
     * @return MorphTo
     */
    public function entity(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return HasMany
     */
    public function likes(): HasMany
    {
        return $this->hasMany(PostLike::class);
    }

    /**
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(PostComment::class);
    }

    /**
     * @return MorphMany
     */
    public function tags(): MorphMany
    {
        return $this->morphMany(Tag::class, 'entity');
    }

    /**
     * @return bool
     */
    public function createdByUser(): bool
    {
        return $this->entity_type === User::class;
    }

    /**
     * @return bool
     */
    public function createdByTeam(): bool
    {
        return $this->entity_type === Team::class;
    }
}
