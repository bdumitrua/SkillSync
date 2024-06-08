<?php

namespace App\Models;

use App\Traits\Elasticsearchable;
use Elastic\Elasticsearch\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Log;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use HasFactory, Searchable, Elasticsearchable {
        Elasticsearchable::search insteadof Searchable;
    }

    protected $fillable = [
        'text',
        'media_url',
        'entity_type',
        'entity_id',
    ];

    protected static function getESIndex(): string
    {
        return 'posts';
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
        ];
    }

    /**
     * Build the search query for Elasticsearch.
     *
     * @param string $searchString
     * @return array
     */
    protected static function getSearchQuery(string $searchString): array
    {
        return [
            'multi_match' => [
                'query' => $searchString,
                'fields' => ['text'],  // Поиск только по индексируемым полям
                "fuzziness" => 2
            ]
        ];
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
        Log::debug('Getting post likes', [
            'postId' => $this->id
        ]);

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
