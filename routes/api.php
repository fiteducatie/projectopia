<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PersonaChatController;

Route::prefix('persona')->group(function () {
    Route::post('{personaId}/chat/stream', [PersonaChatController::class, 'stream']);
});
