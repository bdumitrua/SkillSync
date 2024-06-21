<?php

use Prometheus\RenderTextFormat;
use Illuminate\Support\Facades\Route;
use App\Prometheus\IPrometheusService;

Route::get('/metrics', function () {
    $result = app(IPrometheusService::class)->getMetrics();
    return response($result)->header('Content-Type', RenderTextFormat::MIME_TYPE);
});
