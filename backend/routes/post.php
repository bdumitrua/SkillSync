<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Post\PostCommentController;

Route::prefix('posts')->name('posts.')->group(function () {
    /*
    *   url: /posts/
    *   name: posts.
    */
    Route::controller(PostController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('feed', 'feed')->name('feed');
        Route::get('show/{post}', 'show')->name('show');
        Route::get('search', 'search')->name('search');
        Route::get('user/{user}', 'user')->name('user');
        Route::get('team/{team}', 'team')->name('team');

        Route::post('/', 'create')->name('create');
        Route::put('{post}', 'update')->name('update');
        Route::delete('{post}', 'delete')->name('delete');
    });

    /*
    *   url: /posts/comments/
    *   name: posts.comments.
    */
    Route::prefix('comments')->name('comments.')->controller(PostCommentController::class)->group(function () {
        Route::get('{post}', 'post')->name('post');
        Route::post('{post}', 'create')->name('create');
        Route::delete('{postComment}', 'delete')->name('delete');
    });
});
