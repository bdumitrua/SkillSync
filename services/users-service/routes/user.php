<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->controller(UserController::class)->group(function () {
    Route::middleware(['auth:api'])->group(function () {
        Route::get('/', 'index')->name('authorizedUserProfile');
    });
});
