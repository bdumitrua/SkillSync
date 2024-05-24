<?php

use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserInterestController;
use App\Http\Controllers\User\UserSubscriptionController;
use Illuminate\Support\Facades\Route;

Route::prefix('users')->middleware(['auth:api'])->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/', 'index')->name('authorizedUserData');
        Route::get('show/{user}', 'show')->name('getUserProfile');
        Route::put('/', 'update')->name('updateUserData');
    });

    Route::controller(UserSubscriptionController::class)->group(function () {
        Route::get('subscribers/{user}', 'subscribers')->name('userSubscribers');
        Route::get('subscriptions/{user}', 'subscriptions')->name('userSubscriptions');

        Route::middleware(['prevent.self.action'])->group(function () {
            Route::post('subscribe/{user}', 'subscribe')->name('subscribeOnUser');
            Route::delete('unsubscribe/{user}', 'unsubscribe')->name('unsubscribeFromUser');
        });
    });

    Route::prefix('interests')->controller(UserInterestController::class)->group(function () {
        Route::post('/', 'add')->name('addUserInterest');

        Route::middleware(['checkRights:userInterest'])->group(function () {
            Route::delete('/{userInterest}', 'remove')->name('removeUserInterest');
        });
    });
});
