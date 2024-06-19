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
        *   url: /chats/chats/members/
        *   name: chats.chats.members.
        */
    Route::prefix('{chat}/members')->name('members.')->controller(ChatMemberController::class)->group(function () {
        Route::get('/', 'show')->name('show');
        Route::post('{user}', 'add')->name('add');
        Route::delete('{user}', 'delete')->name('delete');
    });

    /*
    *   url: /chats/messages/
    *   name: chats.messages
    */
    Route::prefix('{chat}/messages')->controller(MessageController::class)->group(function () {
        Route::get('/', 'chat')->name('chat');
        Route::post('send', 'send')->name('send');
        Route::post('read/{messageUuid}', 'read')->name('read');
        Route::delete('delete/{messageUuid}', 'delete')->name('delete');
    });
});
