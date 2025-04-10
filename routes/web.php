<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\PomodoroController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\DashboardController;


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



require __DIR__.'/auth.php';
