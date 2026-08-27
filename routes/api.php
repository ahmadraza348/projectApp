<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProjectController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::post('/login', [AuthController::class, 'login'])->name('api.login');

    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
        Route::get('/me', [AuthController::class, 'me'])->name('api.me');

            Route::middleware('role:admin,manager')->group(function () {
            Route::apiResource('categories', CategoryController::class);
            Route::apiResource('projects', ProjectController::class);
        });
    });
});
