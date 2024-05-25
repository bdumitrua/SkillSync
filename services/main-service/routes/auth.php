<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->name('auth.')->withoutMiddleware([Authenticate::class . ':api'])->controller(AuthController::class)->group(function () {
    Route::post('register', 'register')->name('register');
    Route::post('login', 'login')->name('login');
    Route::get('refresh', 'refresh')->name('refresh');

    Route::middleware([Authenticate::class . ':api'])->group(function () {
        Route::post('logout', 'logout')->name('logout');
    });

    Route::prefix('reset')->name('reset.')->group(function () {
        Route::post('check', 'resetCheck')->name('resetCheck');
        Route::post('confirm/{authReset}', 'resetConfirm')->name('resetConfirm');
        Route::post('end/{authReset}', 'resetEnd')->name('resetEnd');
    });
});
