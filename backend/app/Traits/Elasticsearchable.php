<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Elastic\Elasticsearch\Client;

trait Elasticsearchable
{
    protected static function getESIndex(): string
    {
        return '';
    }

    protected static function getESRefreshInterval(): string
    {
        return '30s';
    }

    protected static function getESSearchSize(): int
    {
        return 20;
    }

    /**
     * Get the Elasticsearch client instance.
     *
     * @return Client
     */
    protected static function getElasticsearchClient(): Client
    {
        return app(Client::class);
    }

    /**
     * Checks if model index already exists
     */
    protected static function elasticsearchIndexExists(): bool
    {
        $client = static::getElasticsearchClient();

        return $client->indices()->exists(['index' => static::getESIndex()])->asBool();
    }

    /**
     * Create the Elasticsearch index with the specified properties and settings.
     */
    public static function createElasticsearchIndex(bool $deleteIfExists = true): void
    {
        $client = static::getElasticsearchClient();

        // Clear (delete) index data if needed
        if ($deleteIfExists) {
            static::deleteElasticsearchIndex();
        }

        // If exists and don't need to delete - stop creating
        if (!$deleteIfExists && static::elasticsearchIndexExists()) {
            return;
        }

        // Define the index parameters
        $params = [
            'index' => static::getESIndex(),
            'body' => [
                'settings' => static::getElasticsearchIndexSettings(),
                'mappings' => [
                    'properties' => static::getSearchProperties()
                ]
            ],
            'pretty' => true
        ];

        // Create the index
        $client->indices()->create($params);
    }

    public static function updateElasticsearchIndex(): void
    {
        static::createElasticsearchIndex(true);
    }

    /**
     * Set the settings for the Elasticsearch index.
     */
    protected static function getElasticsearchIndexSettings(): array
    {
        return [
            'index.store.stats_refresh_interval' => static::getESRefreshInterval(),
            'index.number_of_shards' => 1,
            'index.number_of_replicas' => 0
        ];
    }

    /**
     * Delete the Elasticsearch index if it exists.
     */
    public static function deleteElasticsearchIndex(): void
    {
        $client = static::getElasticsearchClient();

        if (static::elasticsearchIndexExists()) {
            $client->indices()->delete(['index' => static::getESIndex()]);
        }
    }

    /**
     * Add a document to Elasticsearch index.
     * 
     * @param mixed $model
     * @param string $key
     * 
     * @return void
     */
    public static function addToElasticsearch($model, string $key = 'id'): void
    {
        $client = static::getElasticsearchClient();

        $params = [
            'index' => static::getESIndex(),
            'id' => $model->{$key},
            'body' => $model->toSearchableArray()
        ];

        $client->index($params);
    }

    /**
     * Update a document in Elasticsearch index.
     * 
     * @param mixed $model
     * @param string $key
     * 
     * @return void
     */
    public static function updateElasticsearchDocument($model, string $key = 'id'): void
    {
        $client = static::getElasticsearchClient();

        $params = [
            'index' => static::getESIndex(),
            'id' => $model->{$key},
            'body' => [
                'doc' => $model->toSearchableArray()
            ]
        ];

        $client->update($params);
    }

    /**
     * Delete a document from Elasticsearch index.
     * 
     * @param $id
     * 
     * @return void
     */
    public static function deleteElasticsearchDocument($id): void
    {
        $client = static::getElasticsearchClient();

        $params = [
            'index' => static::getESIndex(),
            'id' => $id
        ];

        $client->delete($params);
    }

    /**
     * Perform a search on the Elasticsearch index.
     *
     * @param string $searchString
     * @param int $offset
     * @return Collection
     */
    public static function search(string $searchString, int $offset = 0): Collection
    {
        $params = [
            'index' => static::getESIndex(),
            'body' => [
                'from' => $offset,
                'size' => static::getESSearchSize(),
                'query' => static::getSearchQuery($searchString)
            ]
        ];

        $client = static::getElasticsearchClient();
        $response = $client->search($params)->asArray();

        return static::performElasticsearchSearch($response);
    }

    /**
     * Process the search results from Elasticsearch.
     *
     * @param array $response
     * @return Collection
     */
    protected static function performElasticsearchSearch(array $response): Collection
    {
        // Process search results
        $hits = $response['hits']['hits'];

        $results = [];
        foreach ($hits as $hit) {
            $model = new self($hit['_source']);
            $model->id = $hit['_source']['id'];

            // Add missing attributes from _source to the model
            foreach ($hit['_source'] as $key => $value) {
                if (!property_exists($model, $key)) {
                    $model->{$key} = $value;
                }
            }

            $results[] = $model;
        }

        return new Collection($results);
    }

    /**
     * Define the properties for the Elasticsearch index mappings.
     *
     * @return array
     */
    protected static function getSearchProperties(): array
    {
        return [
            // 'id' => [
            //     'type' => 'keyword'
            // ],
            // 'text' => [
            //     'type' => 'text'
            // ],
            // 'description' => [
            //     'type' => 'text',
            //     'index' => false
            // ],
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
            // 'multi_match' => [
            //     'query' => $searchString,
            //     'fields' => ['text'],  // Поиск только по индексируемым полям
            //     "fuzziness" => 2
            // ]
        ];
    }
}
