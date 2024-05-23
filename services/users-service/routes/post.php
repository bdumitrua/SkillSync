<?php

use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\PostCommentLikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserInterestController;
use App\Http\Controllers\UserSubscriptionController;
use Illuminate\Support\Facades\Route;

// TODO MOVE AUTH TO GLOBAL MIDDLEWARE
// /posts
Route::prefix('posts')->middleware(['auth:api'])->group(function () {
    Route::controller(PostController::class)->group(function () {
        // TODO ADD GLOBAL PREFIXES
        Route::get('/', 'index')->name('posts.index');
        Route::get('feed', 'feed')->name('posts.feed');
        Route::get('show/{post}', 'show')->name('posts.show');
        Route::get('user/{user}', 'user')->name('posts.user');
        Route::get('team/{team}', 'team')->name('posts.team');

        // TODO CHECK RIGHTS
        Route::post('/', 'create')->name('posts.create');
        Route::put('{post}', 'update')->name('posts.update');
        Route::delete('{post}', 'delete')->name('posts.delete');
    });

    // /posts/likes
    Route::prefix('likes')->controller(PostLikeController::class)->group(function () {
        Route::get('show/{post}', 'show')->name('posts.likes.show');
        Route::get('user/{user}', 'user')->name('posts.likes.user');
        Route::post('{post}', 'create')->name('posts.likes.create');
        Route::delete('{post}', 'delete')->name('posts.likes.delete');
    });

    // /posts/comments
    Route::prefix('comments')->controller(PostCommentController::class)->group(function () {
        Route::get('show/{post}', 'show')->name('posts.comments.show');
        Route::post('{post}', 'create')->name('posts.comments.create');
        Route::delete('{postComment}', 'delete')->name('posts.comments.delete');

        // /posts/comments/likes
        Route::prefix('likes')->controller(PostCommentLikeController::class)->group(function () {
            Route::post('{postComment}', 'create')->name('posts.comments.likes.create');
            Route::delete('{postComment}', 'delete')->name('posts.comments.likes.delete');
        });
    });
});
