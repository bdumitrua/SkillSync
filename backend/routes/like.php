<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LikeController;


Route::prefix('likes')->name('likes.')->controller(LikeController::class)->group(function () {
    /*
    *   url: /likes/
    *   name: likes.
    */
    Route::get('user/{user}', 'user')->name('user');
    Route::get('post/{post}', 'post')->name('post');
    Route::post('/', 'create')->name('create');
    Route::delete('/', 'delete')->name('delete');
});
