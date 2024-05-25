<?php

use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserSubscriptionController;
use Illuminate\Support\Facades\Route;

Route::prefix('users')->name('users.')->group(function () {
    /*
    *   url: /users/
    *   name: users.
    */
    Route::controller(UserController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('show/{user}', 'show')->name('show');
        Route::put('/', 'update')->name('update');
    });

    Route::controller(UserSubscriptionController::class)->group(function () {
        Route::get('subscribers/{user}', 'subscribers')->name('subscribers');
        Route::get('subscriptions/{user}', 'subscriptions')->name('subscriptions');

        // TODO REMOVE middleware
        Route::middleware(['prevent.self.action'])->group(function () {
            Route::post('subscribe/{user}', 'subscribe')->name('subscribe');
            Route::delete('unsubscribe/{user}', 'unsubscribe')->name('unsubscribe');
        });
    });
});
