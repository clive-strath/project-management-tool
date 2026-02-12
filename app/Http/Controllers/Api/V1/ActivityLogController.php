<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActivityLogResource;
use App\Models\Project;
use App\Models\Task;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ActivityLogController extends Controller
{
    public function projectLogs(Project $project)
    {
        Gate::authorize('view', $project);

        $logs = ActivityLog::where('subject_type', Project::class)
            ->where('subject_id', $project->id)
            ->orWhere(function ($query) use ($project) {
                $query->where('subject_type', Task::class)
                    ->whereIn('subject_id', function ($subQuery) use ($project) {
                        $subQuery->select('id')
                            ->from('tasks')
                            ->whereIn('task_list_id', function ($listQuery) use ($project) {
                                $listQuery->select('id')
                                    ->from('task_lists')
                                    ->whereIn('board_id', function ($boardQuery) use ($project) {
                                        $boardQuery->select('id')
                                            ->from('boards')
                                            ->where('project_id', $project->id);
                                    });
                            });
                    });
            })
            ->with('user')
            ->latest()
            ->paginate();

        return ActivityLogResource::collection($logs);
    }

    public function taskLogs(Task $task)
    {
        Gate::authorize('view', $task);

        $logs = ActivityLog::where('subject_type', Task::class)
            ->where('subject_id', $task->id)
            ->with('user')
            ->latest()
            ->paginate();

        return ActivityLogResource::collection($logs);
    }
}
