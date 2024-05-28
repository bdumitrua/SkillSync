<?php

namespace App\Traits;

use App\Helpers\TimeHelper;
use App\Prometheus\PrometheusServiceProxy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

trait GetCachedData
{
    /**
     * @param string $cacheKey
     * @param int|null $seconds
     * @param \Closure $callback
     * @param bool $updateCache
     * 
     * @return mixed
     */
    protected function getCachedData(string $cacheKey, ?int $seconds, \Closure $callback, bool $updateCache = false)
    {
        $prometheusService = app(PrometheusServiceProxy::class);
        $cacheKeyForMetrics = explode(':', $cacheKey)[0];

        if ($updateCache || !Cache::has($cacheKey)) {
            $data = $callback();

            $seconds
                ? Cache::put($cacheKey, $data, TimeHelper::getSeconds($seconds))
                : Cache::forever($cacheKey, $data);

            $prometheusService->incrementCacheMiss($cacheKeyForMetrics);
            return $data;
        }

        $prometheusService->incrementCacheHit($cacheKeyForMetrics);
        return Cache::get($cacheKey);
    }

    /**
     * @param array $modelIds
     * @param \Closure $callback
     * 
     * @return Collection
     */
    public function getCachedCollection(array $modelIds, \Closure $callback): Collection
    {
        return new Collection(array_map(function ($modelId) use ($callback) {
            if (!empty($model = $callback($modelId))) {
                return $model;
            }
        }, $modelIds));
    }

    /**
     * @param string $cacheKey
     * 
     * @return void
     */
    protected function clearCache(string $cacheKey): void
    {
        Cache::forget($cacheKey);
    }
}
