<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Redis;
use Illuminate\Console\Command;

class TestRedisLatency extends Command
{
    protected $signature = 'test:redis-latency';
    protected $description = 'Test Redis latency';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $start = microtime(true);

        // Отправляем простую команду к Redis
        Redis::ping();

        $end = microtime(true);

        $latency = ($end - $start) * 1000; // Преобразование в миллисекунды

        $this->info("Redis latency: {$latency} ms");
    }
}
