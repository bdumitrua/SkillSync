<?php

namespace App\Http\Middleware;

use App\Prometheus\IPrometheusService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ErrorTrackingMiddleware
{
    protected $prometheusService;

    public function __construct(IPrometheusService $prometheusService)
    {
        $this->prometheusService = $prometheusService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->isClientError() || $response->isServerError()) {
            $routeName = $request->route()->getName();
            $this->prometheusService->incrementErrorCounter($response->getStatusCode(), $routeName);
        }

        return $response;
    }
}
