<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Closure;
use App\Prometheus\IPrometheusService;
use App\Helpers\Stopwatch;

class PrometheusMiddleware
{
    protected $prometheusService;

    public function __construct(IPrometheusService $prometheusService)
    {
        $this->prometheusService = $prometheusService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()->getName();
        $this->prometheusService->incrementRequestCounter($routeName);
        $stopwatch = new Stopwatch();
        $stopwatch->start();

        $response = $next($request);

        $duration = $stopwatch->stop();
        $this->prometheusService->addResponseTimeHistogram($duration, $routeName);

        return $response;
    }
}
