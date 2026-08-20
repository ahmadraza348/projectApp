<?php

use App\Http\Controllers\{DashboardController, UserController, AuthController, CategoryController, ProjectController};
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('Savelogin');

// Apply both auth and role middleware to the entire admin section
Route::prefix('/admin')
    ->middleware(['auth', 'role:admin,manager,member']) // <--- NEW
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

        // USER MANAGEMENT — only admin can access
        Route::prefix('/user')->name('user.')->group(function () {
                Route::get('/', [UserController::class, 'index'])->name('index')->middleware('role:admin');
                Route::post('/submit', [UserController::class, 'submit'])->name('submit')->middleware('role:admin');
                Route::put('/update/{user}', [UserController::class, 'update'])->name('update')->middleware('role:admin');
                Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy')->middleware('role:admin');
                Route::get('/profile', [UserController::class, 'profile'])->name('profile');
                Route::put('/profile/update', [UserController::class, 'profile_update'])->name('profile.update');
                Route::put('/profile/password', [UserController::class, 'profile_password'])->name('profile.password');
            });

        Route::prefix('/categories')->name('category.')->middleware('role:admin,manager')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('index'); 
            Route::post('/store', [CategoryController::class, 'store'])->name('store'); 
            Route::put('/update/{category}', [CategoryController::class, 'update'])->name('update'); 
            Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy'); 
        });

        Route::prefix('/projects')->name('project.')->middleware('role:admin,manager')->group(function () {
            Route::get('/', [ProjectController::class, 'index'])->name('index'); 
            Route::get('/create', [ProjectController::class, 'create'])->name('create'); 
            Route::post('/store', [ProjectController::class, 'store'])->name('store'); 
            Route::put('/update/{category}', [ProjectController::class, 'update'])->name('update'); 
            Route::delete('/{category}', [ProjectController::class, 'destroy'])->name('destroy'); 
        });  

        Route::prefix('/tasks')->name('tasks.')->middleware('role:admin,manager')->group(function () {
            Route::get('/', [TasksController::class, 'index'])->name('index');
        });

        Route::prefix('/reports')->name('reports.')->middleware('role:admin,manager')->group(function () {
            Route::get('/', [ReportsController::class, 'index'])->name('index');
        });
    });