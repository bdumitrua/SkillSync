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
    Route::post('/', 'create')->name('create');
    Route::delete('{subscription}', 'delete')->name('delete');
});
