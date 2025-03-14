<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\PomodoroController;
use App\Http\Controllers\AnalyticsController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('tasks', TaskController::class);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/pomodoro', [PomodoroController::class, 'index'])->name('pomodoro');
    Route::post('/pomodoro/store-session', [PomodoroController::class, 'storeSession'])->name('pomodoro.storeSession');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
});

require __DIR__.'/auth.php';
