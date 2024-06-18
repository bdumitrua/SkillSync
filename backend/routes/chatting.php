<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Messaging\MessageController;
use App\Http\Controllers\Messaging\ChatMemberController;
use App\Http\Controllers\Messaging\ChatController;

Route::prefix('messages')->name('messages.')->group(function () {
    /*
    *   url: /messages/
    *   name: messages.
    */
    Route::controller(MessageController::class)->group(function () {
        Route::post('send/{chat}', 'send')->name('send');
        Route::post('read/{chat}/{messageUuid}', 'read')->name('read');
        Route::delete('delete/{chat}/{messageUuid}', 'delete')->name('delete');
    });

    /*
    *   url: /messages/chats/
    *   name: messages.chats.
    */
    Route::prefix('chats')->name('chats.')->controller(ChatController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('show/{chat}', 'show')->name('show');
        Route::post('/', 'create')->name('create');

        /*
        *   url: /messages/chats/members/
        *   name: messages.chats.members.
        */
        Route::prefix('members')->name('members.')->controller(ChatMemberController::class)->group(function () {
            Route::get('{chat}', 'show')->name('show');
            Route::post('{chat}/{userId}', 'add')->name('add');
            Route::delete('{chat}/{userId}', 'remove')->name('remove');
        });
    });
});
