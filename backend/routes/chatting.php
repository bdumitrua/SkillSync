<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Messaging\MessageController;
use App\Http\Controllers\Messaging\ChatMemberController;
use App\Http\Controllers\Messaging\ChatController;

Route::prefix('chats')->name('chats.')->group(function () {
    /*
    *   url: /chats/
    *   name: chats.
    */
    Route::controller(ChatController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('search', 'search')->name('search');
        Route::get('show/{chat}', 'show')->name('show');
        Route::post('/', 'create')->name('create');
        Route::put('/{chat}', 'update')->name('update');
    });

    /*
        *   url: /chats/{chat}/members/
        *   name: chats.members.
        */
    Route::prefix('{chat}/members')->name('members.')->controller(ChatMemberController::class)->group(function () {
        Route::get('/', 'show')->name('show');
        Route::post('{user}', 'add')->name('add');
        Route::delete('{user}', 'delete')->name('delete');
    });

    /*
    *   url: /chats/{chat}/messages/
    *   name: chats.messages
    */
    Route::prefix('{chat}/messages')->name('messages.')->controller(MessageController::class)->group(function () {
        Route::get('/', 'chat')->name('chat');
        Route::post('send', 'send')->name('send');
        Route::post('read/{messageUuid}', 'read')->name('read');
        Route::delete('{messageUuid}', 'delete')->name('delete');
    });

    /*
    *   url: /chats/messages/search
    *   name: chats.messages.search
    */
    Route::get('messages/search', [MessageController::class, 'search'])->name('messages.search');
});
