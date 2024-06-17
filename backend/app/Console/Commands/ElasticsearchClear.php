<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Client;

class ElasticsearchClear extends Command
{
    protected $signature = 'elasticsearch:clear';
    protected $description = 'Clear specified Elasticsearch ';

    public function handle()
    {
        $indices = ['users', 'posts', 'projects', 'teams'];
        $client = $this->getElasticsearchClient();

        foreach ($indices as $index) {
            $this->info("Deleting index: $index");

            try {
                $client->indices()->delete(['index' => $index]);
                $this->info("Successfully deleted index: $index");
            } catch (\Exception $e) {
                $this->error("Failed to delete index: $index. Error: " . $e->getMessage());
            }
        }

        // Force merge to expunge deletes and free up space
        $this->info("Performing force merge to expunge deletes and free up space");
        try {
            $client->indices()->forcemerge(['only_expunge_deletes' => true]);
            $this->info("Force merge completed successfully");
        } catch (\Exception $e) {
            $this->error("Force merge failed. Error: " . $e->getMessage());
        }
    }

    protected function getElasticsearchClient()
    {
        return app(Client::class);
    }
}
