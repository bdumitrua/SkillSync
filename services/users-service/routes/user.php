<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('users')->controller(UserController::class)->group(function () {
    Route::middleware(['auth:api'])->group(function () {
        Route::get('/', 'index')->name('authorizedUserProfile');
    });
});
