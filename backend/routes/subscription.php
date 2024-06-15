<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriptionController;


Route::prefix('subscriptions')->name('subscriptions.')->controller(SubscriptionController::class)->group(function () {
    /*
    *   url: /subscriptions/
    *   name: subscriptions.
    */
    Route::get('users/{user}', 'users')->name('users');
    Route::get('teams/{user}', 'teams')->name('teams');
    Route::get('to/user/{user}', 'user')->name('to.user');
    Route::get('to/team/{team}', 'team')->name('to.team');

    Route::post('/', 'create')->name('create');
    Route::delete('/', 'delete')->name('delete');
});
