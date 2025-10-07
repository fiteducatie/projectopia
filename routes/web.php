<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::get('/kies-project', [\App\Http\Controllers\ChooseProjectController::class, 'index'])->name('choose.index');
Route::get('/kies-project/teams', [\App\Http\Controllers\ChooseProjectController::class, 'teams'])->name('choose.teams');
Route::get('/kies-project/teams/{team}', [\App\Http\Controllers\ChooseProjectController::class, 'teamProjects'])->name('choose.team.projects');
Route::get('/kies-project/projects/{project}', [\App\Http\Controllers\ChooseProjectController::class, 'project'])->name('choose.project');
Route::post('/kies-project/projects/{project}/toggle-status', [\App\Http\Controllers\ChooseProjectController::class, 'toggleStatus'])->name('project.toggle-status')->middleware('auth');
