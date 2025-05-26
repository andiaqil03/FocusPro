<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\PomodoroController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('tasks', TaskController::class)->except(['show']);
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/pomodoro', [PomodoroController::class, 'index'])->name('pomodoro');
    Route::post('/pomodoro/store-session', [PomodoroController::class, 'storeSession'])->name('pomodoro.storeSession');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
});

Route::patch('/tasks/{task}/complete', [TaskController::class, 'markAsCompleted'])->name('tasks.complete');
Route::get('/tasks/export-pdf', [TaskController::class, 'exportPdf'])->name('tasks.exportPdf');

Route::get('/analytics/download-pdf', [AnalyticsController::class, 'downloadPdf'])->name('analytics.download');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminDashboardController::class, 'users'])->name('admin.users');
    Route::get('/admin/users/{user}/edit', [AdminDashboardController::class, 'editUser'])->name('admin.users.edit');
    Route::patch('/admin/users/{user}', [AdminDashboardController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminDashboardController::class, 'deleteUser'])->name('admin.users.delete');


});


require __DIR__.'/auth.php';
