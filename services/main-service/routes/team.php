<?php

use App\Http\Controllers\Team\TeamApplicationController;
use App\Http\Controllers\Team\TeamController;
use App\Http\Controllers\Team\TeamLinkController;
use App\Http\Controllers\Team\TeamMemberController;
use App\Http\Controllers\Team\TeamVacancyController;
use Illuminate\Support\Facades\Route;

Route::prefix('teams')->name('teams.')->group(function () {
    /*
    *   url: /teams/
    *   name: teams.
    */
    Route::controller(TeamController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('show/{team}', 'show')->name('show');
        Route::get('user/{user}', 'user')->name('user');

        Route::post('/', 'create')->name('create');
        Route::put('{team}', 'update')->name('update');
        Route::delete('{team}', 'delete')->name('delete');
    });

    /*
    *   url: /teams/applications/
    *   name: teams.applications.
    */
    Route::prefix('applications')->name('applications.')->controller(TeamApplicationController::class)->group(function () {
        Route::get('show/{teamApplication}', 'show')->name('show');
        Route::get('team/{team}', 'team')->name('team');
        Route::get('vacancy/{teamVacancy}', 'vacancy')->name('vacancy');

        Route::post('{teamVacancy}', 'create')->name('create');
        Route::patch('{teamApplication}', 'update')->name('update');
        Route::delete('{teamApplication}', 'delete')->name('delete');
    });

    /*
    *   url: /teams/links/
    *   name: teams.links.
    */
    Route::prefix('links')->name('links.')->controller(TeamLinkController::class)->group(function () {
        Route::get('{team}', 'team')->name('team');
        Route::post('{team}', 'create')->name('create');
        Route::put('{teamLink}', 'update')->name('update');
        Route::delete('{teamLink}', 'delete')->name('delete');
    });

    /*
    *   url: /teams/members/
    *   name: teams.members.
    */
    Route::prefix('members')->name('members.')->controller(TeamMemberController::class)->group(function () {
        Route::get('{team}', 'team')->name('team');
        Route::post('{team}', 'create')->name('create');
        Route::delete('{team}', 'delete')->name('delete');
    });

    /*
    *   url: /teams/vacancies/
    *   name: teams.vacancies.
    */
    Route::prefix('vacancies')->name('vacancies.')->controller(TeamVacancyController::class)->group(function () {
        Route::get('show/{teamVacancy}', 'show')->name('show');
        Route::get('team/{team}', 'team')->name('team');
        Route::post('{team}', 'create')->name('create');
        Route::put('{teamVacancy}', 'update')->name('update');
        Route::delete('{teamVacancy}', 'delete')->name('delete');
    });
});
