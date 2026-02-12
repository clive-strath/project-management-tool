<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\BoardController;
use Illuminate\Support\Facades\Route;

// Main application route
Route::get('/', function () {
    return view('app');
});

// Breeze authentication routes are automatically included

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/projects', [ProjectsController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjectsController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectsController::class, 'store'])->name('projects.store');
    Route::get('/projects/{id}', [ProjectsController::class, 'show'])->name('projects.show');

    Route::get('/board', [BoardController::class, 'index'])->name('board');
    Route::post('/board/tasks', [BoardController::class, 'createTask'])->name('board.tasks.create');
    Route::put('/board/tasks/{taskId}/status', [BoardController::class, 'updateTaskStatus'])->name('board.tasks.update-status');
    Route::get('/board/tasks/{taskId}', [BoardController::class, 'getTaskDetails'])->name('board.tasks.show');

    Route::get('/time-tracking', function () {
        return view('time-tracking.index');
    })->name('time-tracking');

    Route::get('/reports', function () {
        return view('reports.index');
    })->name('reports');

    Route::get('/team', function () {
        return view('team.index');
    })->name('team');

    Route::get('/settings', function () {
        return view('settings.index');
    })->name('settings');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
