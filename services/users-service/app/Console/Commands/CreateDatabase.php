<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateDatabase extends Command
{
    protected $signature = 'create:db';

    protected $description = 'Create database from .env';

    public function handle()
    {
        $databaseName = config('database.connections.mysql.database');
        $charset = config('database.connections.mysql.charset', 'utf8mb4');
        $collation = config('database.connections.mysql.collation', 'utf8mb4_unicode_ci');

        config(["database.connections.mysql.database" => null]);

        $query = "CREATE DATABASE IF NOT EXISTS $databaseName CHARACTER SET $charset COLLATE $collation;";

        DB::statement($query);

        config(["database.connections.mysql.database" => $databaseName]);

        $this->info("The command was successful!");
    }
}
