<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::get('/kies-activiteit', [\App\Http\Controllers\ChooseActivityController::class, 'index'])->name('choose.index');
Route::get('/kies-activiteit/teams', [\App\Http\Controllers\ChooseActivityController::class, 'teams'])->name('choose.teams');
Route::get('/kies-activiteit/teams/{team}', [\App\Http\Controllers\ChooseActivityController::class, 'teamActivities'])->name('choose.team.activities');
Route::get('/kies-activiteit/activities/{activity}', [\App\Http\Controllers\ChooseActivityController::class, 'activity'])->name('choose.activity');
Route::post('/kies-activiteit/activities/{activity}/toggle-status', [\App\Http\Controllers\ChooseActivityController::class, 'toggleStatus'])->name('activity.toggle-status')->middleware('auth');
