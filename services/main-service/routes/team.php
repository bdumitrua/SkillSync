<?php

use App\Http\Controllers\Team\TeamApplicationController;
use App\Http\Controllers\Team\TeamController;
use App\Http\Controllers\Team\TeamLinkController;
use App\Http\Controllers\Team\TeamMemberController;
use App\Http\Controllers\Team\TeamScopeController;
use App\Http\Controllers\Team\TeamVacancyController;
use Illuminate\Support\Facades\Route;

// /teams
Route::prefix('teams')->middleware(['auth:api'])->group(function () {
    Route::controller(TeamController::class)->group(function () {
        Route::get('/', 'index')->name('teams.index');
        Route::get('show/{post}', 'show')->name('teams.show');
        Route::get('user/{user}', 'user')->name('teams.user');

        Route::post('/', 'create')->name('teams.create');
        Route::put('{post}', 'update')->name('teams.update');
        Route::delete('{post}', 'delete')->name('teams.delete');
    });

    // /teams/applications
    Route::prefix('applications')->controller(TeamApplicationController::class)->group(function () {
        Route::get('show/{teamApplication}', 'show')->name('teams.applications.show');
        Route::get('team/{team}', 'team')->name('teams.applications.team');
        Route::get('vacancy/{teamVacancy}', 'vacancy')->name('teams.applications.vacancy');

        Route::post('{teamVacancy}', 'create')->name('teams.applications.create');
        Route::patch('{teamApplication}', 'update')->name('teams.applications.update');
        Route::delete('{teamApplication}', 'delete')->name('teams.applications.delete');
    });

    // /teams/links
    Route::prefix('links')->controller(TeamLinkController::class)->group(function () {
        Route::get('{team}', 'team')->name('teams.links.team');
        Route::post('{team}', 'create')->name('teams.links.create');
        Route::put('{teamLink}', 'update')->name('teams.links.update');
        Route::delete('{teamLink}', 'delete')->name('teams.links.delete');
    });

    // /teams/members
    Route::prefix('members')->controller(TeamMemberController::class)->group(function () {
        Route::get('{team}', 'team')->name('teams.members.team');
        Route::post('{team}', 'create')->name('teams.members.create');
        Route::delete('{team}', 'delete')->name('teams.members.delete');
    });

    // /teams/scopes
    Route::prefix('scopes')->controller(TeamScopeController::class)->group(function () {
        Route::get('{team}', 'team')->name('teams.scopes.team');
        Route::post('{team}', 'create')->name('teams.scopes.create');
        Route::delete('{teamScope}', 'delete')->name('teams.scopes.delete');
    });

    // /teams/vacancies
    Route::prefix('vacancies')->controller(TeamVacancyController::class)->group(function () {
        Route::get('show/{teamVacancy}', 'show')->name('teams.vacancies.show');
        Route::get('team/{team}', 'team')->name('teams.vacancies.team');
        Route::post('{team}', 'create')->name('teams.vacancies.create');
        Route::put('{teamVacancy}', 'update')->name('teams.vacancies.update');
        Route::delete('{teamVacancy}', 'delete')->name('teams.vacancies.delete');
    });
});
