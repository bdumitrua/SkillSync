<?php

use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;


Route::prefix('tags')->name('tags.')->controller(TagController::class)->group(function () {
    /*
    *   url: /tags/
    *   name: tags.
    */
    Route::post('/', 'create')->name('create');
    Route::delete('{tag}', 'delete')->name('delete');
});
