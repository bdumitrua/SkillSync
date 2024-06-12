<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->environment('local')) {
            DB::listen(function ($query) {
                Log::info('Query Time: ' . $query->time . 'ms', [
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
