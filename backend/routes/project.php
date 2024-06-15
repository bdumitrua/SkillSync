<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Project\ProjectMemberController;
use App\Http\Controllers\Project\ProjectLinkController;
use App\Http\Controllers\Project\ProjectController;

Route::prefix('projects')->name('projects.')->group(function () {
    /*
    *   url: /projects/
    *   name: projects.
    */
    Route::controller(ProjectController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('show/{project}', 'show')->name('show');
        Route::get('team/{team}', 'team')->name('team');
        Route::get('user/member/{user}', 'member')->name('member');
        Route::get('user/author/{user}', 'author')->name('author');

        Route::post('/', 'create')->name('create');
        Route::put('{project}', 'update')->name('update');
        Route::delete('{project}', 'delete')->name('delete');
    });

    /*
    *   url: /projects/members/
    *   name: projects.members.
    */
    Route::prefix('{project}/members')->name('members.')->controller(ProjectMemberController::class)->group(function () {
        Route::get('/', 'project')->name('project');
        Route::put('/{user}', 'update')->name('update');
        Route::delet('/{user}', 'delete')->name('delete');
    });

    /*
    *   url: /projects/links/
    *   name: projects.links.
    */
    Route::prefix('{project}/links')->name('links.')->controller(ProjectLinkController::class)->group(function () {
        Route::get('/', 'project')->name('project');
        Route::put('{projectLink}', 'update')->name('update');
        Route::delet('{projectLink}', 'delete')->name('delete');
    });
});
