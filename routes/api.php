<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ProjectController;
use App\Http\Controllers\Api\V1\BoardController;
use App\Http\Controllers\Api\V1\TaskListController;
use App\Http\Controllers\Api\V1\TaskController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\ActivityLogController;
use App\Http\Controllers\Api\V1\TaskAttachmentController;

use App\Http\Controllers\Api\V1\TimeEntryController;
use App\Http\Controllers\Api\V1\ReportController;

Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::apiResource('projects', ProjectController::class);
        Route::apiResource('projects.boards', BoardController::class)->shallow();
        Route::apiResource('boards.task-lists', TaskListController::class)->shallow();
        Route::apiResource('task-lists.tasks', TaskController::class)->shallow();
        Route::apiResource('tasks.comments', CommentController::class)->shallow()->only(['index', 'store', 'destroy']);
        
        Route::get('/projects/{project}/activity', [ActivityLogController::class, 'projectLogs']);
        Route::get('/tasks/{task}/activity', [ActivityLogController::class, 'taskLogs']);

        Route::apiResource('tasks.attachments', TaskAttachmentController::class)->shallow()->only(['index', 'store', 'destroy']);
        Route::get('/attachments/{attachment}/download', [TaskAttachmentController::class, 'download']);

        Route::apiResource('tasks.time-entries', TimeEntryController::class)->shallow();
        Route::get('/projects/{project}/reports/summary', [ReportController::class, 'projectSummary']);
        Route::get('/tasks/{task}/reports/summary', [ReportController::class, 'taskSummary']);
    });
});

