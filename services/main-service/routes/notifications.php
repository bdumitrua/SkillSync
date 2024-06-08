<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;


Route::prefix('notifications')->name('notifications.')->controller(NotificationController::class)->group(function () {
    /*
    *   url: /notifications/
    *   name: notifications.
    */
    Route::get('index', 'index')->name('index');
    Route::patch('{notification}', 'seen')->name('seen');
    Route::delete('{notification}', 'delete')->name('delete');
});
