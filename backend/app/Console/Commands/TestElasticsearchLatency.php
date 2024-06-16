<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Redis;
use Illuminate\Console\Command;
use Elastic\Elasticsearch\Client;

class TestElasticsearchLatency extends Command
{
    protected $signature = 'test:elasticsearch-latency';
    protected $description = 'Test Elasticsearch latency';
    protected $client;

    public function __construct()
    {
        parent::__construct();

        // Laravel will execute ALL _construct() methods for ALL commands whenever a SINGLE command is
        // executed. This leads to noticeable slow-downs and class calls, so be carefully.
        $this->client = app(Client::class);
    }

    public function handle()
    {
        $client = app(Client::class);

        try {
            $startTime = microtime(true);
            $response = $client->ping();
            $endTime = microtime(true);

            if ($response) {
                $latency = ($endTime - $startTime) * 1000; // Convert to milliseconds
                $this->info('Elasticsearch is up. Latency: ' . $latency . ' ms');
            } else {
                $this->error('Elasticsearch did not respond.');
            }
        } catch (\Exception $e) {
            $this->error('Error pinging Elasticsearch: ' . $e->getMessage());
        }
    }
}
