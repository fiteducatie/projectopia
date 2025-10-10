<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PersonaChatController;
use App\Http\Controllers\Api\TeamleaderChatController;
use App\Http\Controllers\Api\ScheduleHistoryController;

Route::prefix('persona')->group(function () {
    Route::post('{personaId}/chat/stream', [PersonaChatController::class, 'stream']);
});

        Route::prefix('teamleader')->group(function () {
            Route::post('{teamleaderId}/chat/stream', [TeamleaderChatController::class, 'stream']);
            Route::get('{teamleaderId}/messages', [TeamleaderChatController::class, 'getMessages']);
            Route::get('{teamleaderId}/schedule/pending', [TeamleaderChatController::class, 'hasPendingScheduleMessage']);
            Route::post('{teamleaderId}/schedule/mark-read', [TeamleaderChatController::class, 'markScheduleAsRead']);
        });

Route::prefix('schedule')->group(function () {
    Route::get('activity/{activity}/history', [ScheduleHistoryController::class, 'getHistory']);
    Route::get('activity/{activity}/completed', [ScheduleHistoryController::class, 'getCompleted']);
    Route::get('activity/{activity}/upcoming', [ScheduleHistoryController::class, 'getUpcoming']);
    Route::get('activity/{activity}/active', [ScheduleHistoryController::class, 'getActive']);
    Route::get('activity/{activity}/stats', [ScheduleHistoryController::class, 'getStats']);
});
