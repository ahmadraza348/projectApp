<?php
use App\Http\Controllers\{DashboardController, UserController, AuthController};

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('Savelogin');

Route::prefix('/admin')->middleware('auth')->group(function(){
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');   

    Route::prefix('/user')->name('user.')->group(function(){
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/submit', [UserController::class, 'submit'])->name('submit');
        Route::put('/update/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::get('/profile', [UserController::class, 'profile'])->name('profile');
        Route::put('/profile/update', [UserController::class, 'profile_update'])->name('profile.update');
        Route::put('/profile/password', [UserController::class, 'profile_password'])->name('profile.password');        
    });
    Route::prefix('/categories')->name('categories.')->group(function(){
         Route::get('/', [CategoriesController::class, 'index'])->name('index');
    });
    Route::prefix('/projects')->name('projects.')->group(function(){
         Route::get('/', [ProjectsController::class, 'index'])->name('index');
    });
    Route::prefix('/tasks')->name('tasks.')->group(function(){
         Route::get('/', [TasksController::class, 'index'])->name('index');
    });
    Route::prefix('/reports')->name('reports.')->group(function(){
         Route::get('/', [ReportsController::class, 'index'])->name('index');
    });
});