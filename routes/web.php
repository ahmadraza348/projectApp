<?php

use App\Http\Controllers\{DashboardController, UserController, AuthController, CategoryController, ProjectController, TaskController};
use App\Http\Controllers\ReportController;
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
        Route::prefix('/user')->name('user.')->controller(UserController::class)->group(function () {
            Route::get('/', 'index')->name('index')->middleware('role:admin');
            Route::post('/submit',  'submit')->name('submit')->middleware('role:admin');
            Route::put('/update/{user}', 'update')->name('update')->middleware('role:admin');
            Route::delete('/{user}',  'destroy')->name('destroy')->middleware('role:admin');
            Route::get('/profile',  'profile')->name('profile');
            Route::put('/profile/update',  'profile_update')->name('profile.update');
            Route::put('/profile/password',  'profile_password')->name('profile.password');
        });

        Route::prefix('/categories')->name('category.')->controller(CategoryController::class)->middleware('role:admin,manager')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/store', 'store')->name('store');
            Route::put('/update/{category}', 'update')->name('update');
            Route::delete('/{category}', 'destroy')->name('destroy');
        });

        Route::prefix('/projects')->name('project.')->controller(ProjectController::class)->middleware('role:admin,manager')->group(function () {
            Route::get('/',  'index')->name('index');
            Route::get('/create',  'create')->name('create');
            Route::post('/store',  'store')->name('store');
            Route::get('/show/{project}',  'show')->name('show');
            Route::get('/edit/{project}',  'edit')->name('edit');
            Route::put('/update/{project}',  'update')->name('update');
            Route::delete('/{project}',  'destroy')->name('destroy');
        });
        Route::prefix('/tasks')->name('task.')->controller(TaskController::class)->middleware('role:admin,manager, member')->group(function () {
            Route::get('/',  'index')->name('index');
            Route::get('/create',  'create')->name('create');
            Route::get('/show',  'show')->name('show');
            Route::post('/store',  'store')->name('store');
            Route::put('/update/{category}',  'update')->name('update');
            Route::delete('/{category}',  'destroy')->name('destroy');
        });
        Route::prefix('/reports')->name('report.')->controller(ReportController::class)->middleware('role:admin')->group(function () {
            Route::get('/',  'index')->name('index');
            Route::get('/create',  'create')->name('create');
            Route::get('/show',  'show')->name('show');
            Route::post('/store',  'store')->name('store');
            Route::put('/update/{category}',  'update')->name('update');
            Route::delete('/{category}',  'destroy')->name('destroy');
        });

    });
