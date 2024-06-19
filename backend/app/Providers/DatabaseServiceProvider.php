<?php

namespace App\Providers;

use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Factory;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Firebase\FirebaseServiceInterface;
use App\Firebase\FirebaseService;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Firebase
        $this->app->bind(FirebaseServiceInterface::class, FirebaseService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->environment('local')) {
            DB::listen(function ($query) {
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/sql.log'),
                ])->info('Query Time: ' . $query->time . 'ms', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                ]);
            });
        }

        // DB::listen(function ($query) {
        //     /** @var PrometheusServiceProxy */
        //     $prometheusService = app(PrometheusServiceProxy::class);
        //     $source = optional(request()->route())->getActionName() ?? 'unknown';
        //     $executionTimeInSeconds = floatval($query->time) / 1000;

        //     $prometheusService->incrementDatabaseQueryCount($source);
        //     $prometheusService->addDatabaseQueryTimeHistogram($executionTimeInSeconds, $source);
        // });
    }
}
