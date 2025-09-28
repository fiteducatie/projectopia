<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PersonaChatController;
use App\Http\Controllers\Api\TeamleaderChatController;

Route::prefix('persona')->group(function () {
    Route::post('{personaId}/chat/stream', [PersonaChatController::class, 'stream']);
});

Route::prefix('teamleader')->group(function () {
    Route::post('{teamleaderId}/chat/stream', [TeamleaderChatController::class, 'stream']);
});
