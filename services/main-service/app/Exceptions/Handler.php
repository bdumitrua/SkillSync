<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof HttpException) {
            return response()->json(
                'error: ' . $exception->getMessage(),
                $exception->getStatusCode()
            );
        }

        if ($exception instanceof ModelNotFoundException) {
            return response()->json(
                'error: ' . $exception->getMessage(),
                404
            );
        }

        return parent::render($request, $exception);
    }
}
