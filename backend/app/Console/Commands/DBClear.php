<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Command;
use App\Prometheus\IPrometheusService;
use App\Models\User;
use App\Models\Team;
use App\Models\Project;
use App\Models\Post;
use App\Models\NoSQL\Message;
use App\Models\GroupChat;
use App\Models\DialogChat;
use App\Firebase\FirebaseService;

class DBClear extends Command
{
    protected $signature = 'db:clear';

    protected $description = 'Clears data from all used databases';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (env('APP_ENV') !== 'local') {
            $this->error('Wipe is not available in not-local environment');
            return;
        }

        $this->info('Deleting elasticsearch indexes');
        User::deleteElasticsearchIndex();
        Team::deleteElasticsearchIndex();
        Post::deleteElasticsearchIndex();
        Project::deleteElasticsearchIndex();
        DialogChat::deleteElasticsearchIndex();
        GroupChat::deleteElasticsearchIndex();
        Message::deleteElasticsearchIndex();
        $this->info('Elasticsearch indexes deleted');

        /** @var FirebaseService */
        $firebaseService = app(FirebaseService::class);
        $firebaseService->wipeMyData();
        $this->info('Firebase bucket cleared');

        // Fresh migrate the database
        $this->info('Starting database migration');
        $this->call('migrate:fresh', [
            '--force' => true,
        ]);
        $this->info('Database migrated fresh');

        // Clear the cache
        $this->info('Starting cache clear');
        $this->call('cache:clear');
        $this->info('Cache cleared');

        app(IPrometheusService::class)->clearMetrics();
        $this->info('Metrics cleared');

        $this->info('End!');
    }
}
