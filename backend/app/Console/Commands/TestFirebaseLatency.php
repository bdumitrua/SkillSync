<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Firebase\FirebaseService;

class TestFirebaseLatency extends Command
{
    protected $signature = 'test:firebase-latency';
    protected $description = 'Test Firebase latency';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /** @var FirebaseService */
        $firebaseService = app(FirebaseService::class);

        // Вызов функции и вывод результата
        $latency = round($firebaseService->measureLatency(), 0);
        echo "Задержка подключения к Firebase: $latency мс";
    }
}
