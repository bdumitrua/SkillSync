<?php

use App\Http\Controllers\Messaging\ChatController;
use App\Http\Controllers\Messaging\ChatMemberController;
use App\Http\Controllers\Messaging\MessageController;
use Illuminate\Support\Facades\Route;

Route::prefix('messages')->name('messages.')->group(function () {
    /*
    *   url: /messages/
    *   name: messages.
    */
    Route::controller(MessageController::class)->group(function () {
        Route::post('send/{chatId}', 'send')->name('send');
        Route::post('read/{chatId}/{messageUuid}', 'read')->name('read');
        Route::delete('delete/{chatId}/{messageUuid}', 'delete')->name('delete');
    });

    /*
    *   url: /messages/chats/
    *   name: messages.chats.
    */
    Route::prefix('chats')->name('chats.')->controller(ChatController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('show/{chatId}', 'show')->name('show');
        Route::post('/', 'create')->name('create');

        /*
        *   url: /messages/chats/members/
        *   name: messages.chats.members.
        */
        Route::prefix('members')->name('members.')->controller(ChatMemberController::class)->group(function () {
            Route::get('{chatId}', 'show')->name('show');
            Route::post('{chatId}/{userId}', 'add')->name('add');
            Route::delete('{chatId}/{userId}', 'remove')->name('remove');
        });
    });
});
