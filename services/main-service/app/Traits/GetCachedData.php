<?php

namespace App\Traits;

use App\Helpers\TimeHelper;
use App\Prometheus\PrometheusServiceProxy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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
        Log::debug("Trying to get data from cache", [
            'cacheKey' => $cacheKey,
        ]);

        $prometheusService = app(PrometheusServiceProxy::class);
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
     * 
     * @return Collection
     */
    public function getCachedCollection(array $modelIds, \Closure $callback): Collection
    {
        return new Collection(array_map(function ($modelId) use ($callback) {
            if (!empty($queryData = $callback($modelId))) {
                return $queryData;
            }
        }, $modelIds));
    }

    /**
     * @param array $modelIds
     * @param \Closure $callback
     * 
     * @return array
     */
    public function getCachedArray(array $modelIds, \Closure $callback): array
    {
        $dataArray = [];

        array_map(function ($modelId) use ($callback, &$dataArray) {
            if (!empty($queryData = $callback($modelId))) {
                $dataArray[$modelId] = $queryData;
            }
        }, $modelIds);

        return $dataArray;
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
