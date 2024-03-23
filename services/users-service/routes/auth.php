<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('register', 'register')->name('authRegister');
    Route::post('login', 'login')->name('authLogin');
    Route::get('refresh', 'refresh')->name('authRefresh');

    Route::middleware(['auth:api'])->group(function () {
        Route::post('logout', 'logout')->name('authLogout');
    });

    Route::prefix('reset')->group(function () {
        Route::post('check', 'resetCheck')->name('checkPasswordReset');
        Route::post('confirm/{authReset}', 'resetConfirm')->name('confirmPasswordReset');
        Route::post('end/{authReset}', 'resetEnd')->name('endPasswordReset');
    });
});
