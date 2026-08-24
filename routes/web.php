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
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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
            Route::post('/{project}/members', 'addMember')->name('members.add');
        });
        Route::prefix('/tasks')->name('task.')->controller(TaskController::class)->middleware('role:admin,manager,member')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/{task}/edit', 'edit')->name('edit');
            Route::put('/{task}', 'update')->name('update');
            Route::delete('/{task}', 'destroy')->name('destroy');
            Route::patch('/{task}/status', 'updateStatus')->name('update-status');
            Route::get('/api/projects/{project}/members', 'getMembers')->name('project.members');

            // New: comments / time logs / attachments
            Route::post('/{task}/comments', [\App\Http\Controllers\TaskCommentController::class, 'store'])->name('comments.store');
            Route::delete('/comments/{comment}', [\App\Http\Controllers\TaskCommentController::class, 'destroy'])->name('comments.destroy');

            Route::post('/{task}/time-logs', [\App\Http\Controllers\TaskTimeLogController::class, 'store'])->name('time-logs.store');
            Route::delete('/time-logs/{timeLog}', [\App\Http\Controllers\TaskTimeLogController::class, 'destroy'])->name('time-logs.destroy');

            Route::post('/{task}/attachments', [\App\Http\Controllers\TaskAttachmentController::class, 'store'])->name('attachments.store');
            Route::delete('/attachments/{attachment}', [\App\Http\Controllers\TaskAttachmentController::class, 'destroy'])->name('attachments.destroy');

            Route::get('/{task}', 'show')->name('show'); // keep last
        });
        // Only 'index' is implemented on ReportController / used by the Reports view —
        // the create/show/store/update/destroy routes previously here pointed at
        // controller methods that don't exist and would 500 if ever hit.
        Route::prefix('/reports')->name('report.')->controller(ReportController::class)->middleware('role:admin')->group(function () {
            Route::get('/',  'index')->name('index');
        });
    });
