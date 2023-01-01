<?php

use App\Http\Controllers\PhotoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'info']);
    Route::apiResource('photos', PhotoController::class)->only('store');
});

Route::post('/trigger', [PhotoController::class, 'trigger']);
