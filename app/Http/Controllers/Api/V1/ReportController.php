<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\TimeEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function projectSummary(Project $project)
    {
        Gate::authorize('view', $project);

        $summary = TimeEntry::whereIn('task_id', function ($query) use ($project) {
                $query->select('id')
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
            })
            ->select('user_id', DB::raw('SUM(duration) as total_duration'))
            ->groupBy('user_id')
            ->with('user')
            ->get();

        return response()->json([
            'project_id' => $project->id,
            'total_project_duration' => $summary->sum('total_duration'),
            'user_breakdown' => $summary,
        ]);
    }

    public function taskSummary(Task $task)
    {
        Gate::authorize('view', $task);

        $totalDuration = $task->timeEntries()->sum('duration');

        return response()->json([
            'task_id' => $task->id,
            'total_duration' => $totalDuration,
            'entries_count' => $task->timeEntries()->count(),
        ]);
    }
}
