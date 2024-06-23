<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Collection;
use App\Prometheus\IPrometheusService;
use App\Helpers\TimeHelper;

trait Cacheable
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
        Log::debug("Trying to get data from cache", [
            'cacheKey' => $cacheKey,
        ]);

        $prometheusService = app(IPrometheusService::class);
        $cacheKeyForMetrics = explode(':', $cacheKey)[0];

        if ($updateCache || !Cache::has($cacheKey)) {
            Log::debug("Cache not found, getting data", [
                'cacheKey' => $cacheKey,
            ]);

            $data = $callback();

            $seconds
                ? Cache::put($cacheKey, $data, TimeHelper::getSeconds($seconds))
                : Cache::forever($cacheKey, $data);

            $prometheusService->incrementCacheMiss($cacheKeyForMetrics);

            Log::debug("Succesfully got data and updated cacheKey data", [
                'cacheKey' => $cacheKey,
            ]);

            return $data;
        }

        Log::debug("Cache found, returning data from cache", [
            'cacheKey' => $cacheKey,
        ]);

        $prometheusService->incrementCacheHit($cacheKeyForMetrics);
        return Cache::get($cacheKey);
    }

    /**
     * @param array $modelIds
     * @param \Closure $callback
     * @param mixed ...$params
     * 
     * @return Collection
     */
    public function getCachedCollection(array $modelIds, \Closure $callback, ...$params): Collection
    {
        $data = array_map(function ($modelId) use ($callback, $params) {
            if (!empty($queryData = $callback($modelId, ...$params))) {
                return $queryData;
            }
        }, $modelIds);

        if ($data instanceof Collection) {
            return $data;
        }

        return new Collection($data);
    }

    /**
     * @param string $cacheKey
     * 
     * @return void
     */
    protected function clearCache(string $cacheKey): void
    {
        Log::debug('Clearing cache by key', [
            'cacheKey' => $cacheKey
        ]);

        Cache::forget($cacheKey);
    }
}
