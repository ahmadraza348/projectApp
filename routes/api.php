<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskAttachmentController;
use App\Http\Controllers\Api\TaskCommentController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TaskTimeLogController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ReportsController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::post('/login', [AuthController::class, 'login'])->name('api.login');

    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
        Route::get('/me', [AuthController::class, 'me'])->name('api.me');

        // Any authenticated user manages their own profile, regardless of role.
        Route::get('/profile', [UserController::class, 'profile'])->name('api.profile');
        Route::put('/profile', [UserController::class, 'updateProfile'])->name('api.profile.update');
        Route::put('/profile/password', [UserController::class, 'updatePassword'])->name('api.profile.password');

        // Categories & Projects — admin/manager only, matching the web routes.
        Route::middleware('role:admin,manager')->group(function () {
            Route::apiResource('categories', CategoryController::class);
            Route::apiResource('projects', ProjectController::class);

            Route::get('/projects/{project}/members', [ProjectController::class, 'members'])->name('api.projects.members');
            Route::post('/projects/{project}/members', [ProjectController::class, 'addMember'])->name('api.projects.members.add');
        });

        // User management — admin only, matching UserPolicy.
        Route::middleware('role:admin')->group(function () {
            Route::apiResource('users', UserController::class);
            Route::get('reports', [ReportsController::class, 'index'])->name('api.reports.index');
        });

        // Tasks — admin/manager/member, matching TaskPolicy (members are scoped to
        // their own tasks inside TaskService/TaskPolicy, not by this middleware).
        Route::middleware('role:admin,manager,member')->group(function () {
            Route::apiResource('tasks', TaskController::class);
            Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('api.tasks.status');

            // Comments
            Route::get('/tasks/{task}/comments', [TaskCommentController::class, 'index'])->name('api.tasks.comments.index');
            Route::post('/tasks/{task}/comments', [TaskCommentController::class, 'store'])->name('api.tasks.comments.store');
            Route::delete('/comments/{comment}', [TaskCommentController::class, 'destroy'])->name('api.comments.destroy');

            // Time logs
            Route::get('/tasks/{task}/time-logs', [TaskTimeLogController::class, 'index'])->name('api.tasks.time-logs.index');
            Route::post('/tasks/{task}/time-logs', [TaskTimeLogController::class, 'store'])->name('api.tasks.time-logs.store');
            Route::delete('/time-logs/{timeLog}', [TaskTimeLogController::class, 'destroy'])->name('api.time-logs.destroy');

            // Attachments
            Route::get('/tasks/{task}/attachments', [TaskAttachmentController::class, 'index'])->name('api.tasks.attachments.index');
            Route::post('/tasks/{task}/attachments', [TaskAttachmentController::class, 'store'])->name('api.tasks.attachments.store');
            Route::delete('/attachments/{attachment}', [TaskAttachmentController::class, 'destroy'])->name('api.attachments.destroy');
        });
    });
});
