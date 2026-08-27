<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProjectController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

Route::prefix('v1')->group(function () {

    Route::post('/login', [AuthController::class, 'login'])->name('api.login');

    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
        Route::get('/me', [AuthController::class, 'me'])->name('api.me');

            Route::middleware('role:admin,manager')->group(function () {
            Route::apiResource('categories', CategoryController::class);
            Route::apiResource('projects', ProjectController::class);
            Route::apiResource('users', UserController::class);
            // Authenticated profile management routes
            Route::get('/profile', [UserController::class, 'profile'])->name('api.profile');
            Route::put('/profile', [UserController::class, 'updateProfile'])->name('api.profile.update');
            Route::put('/profile/password', [UserController::class, 'updatePassword'])->name('api.profile.password');
        });
    });
});
