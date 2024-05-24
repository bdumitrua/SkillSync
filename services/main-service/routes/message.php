<?php

use App\Http\Controllers\Messaging\ChatController;
use App\Http\Controllers\Messaging\ChatMemberController;
use App\Http\Controllers\Messaging\MessageController;
use Illuminate\Support\Facades\Route;

// /messages
Route::prefix('messages')->middleware(['auth:api'])->group(function () {
    Route::controller(MessageController::class)->group(function () {
        Route::post('send/{chatId}', 'send')->name('messages.send');
        Route::post('read/{chatId}/{messageUuid}', 'read')->name('messages.read');
        Route::delete('delete/{chatId}/{messageUuid}', 'delete')->name('messages.delete');
    });

    // /messages/chats
    Route::prefix('chats')->controller(ChatController::class)->group(function () {
        Route::get('index', 'index')->name('messages.chats.index');
        Route::get('show/{chatId}', 'show')->name('messages.chats.show');
        Route::post('/', 'create')->name('messages.chats.create');

        // /messages/chats/members
        Route::prefix('members')->controller(ChatMemberController::class)->group(function () {
            Route::get('{chatId}', 'show')->name('messages.chats.members.show');
            Route::post('{chatId}/{userId}', 'add')->name('messages.chats.members.add');
            Route::delete('{chatId}/{userId}', 'remove')->name('messages.chats.members.remove');
        });
    });
});
