<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get user's projects
        $projects = \App\Models\Project::where(function($query) use ($user) {
            $query->whereHas('members', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->orWhere('owner_id', $user->id);
        })
        ->with(['owner', 'boards.taskLists.tasks'])
        ->get();
        
        // Get all tasks from user's projects
        $allTasks = collect();
        foreach ($projects as $project) {
            foreach ($project->boards as $board) {
                foreach ($board->taskLists as $taskList) {
                    $allTasks = $allTasks->merge($taskList->tasks);
                }
            }
        }
        
        // Calculate statistics
        $activeProjectsCount = $projects->where('status', 'active')->count();
        $totalTasksCount = $allTasks->count();
        $completedTasksCount = $allTasks->where('status', 'completed')->count();
        $completionPercentage = $totalTasksCount > 0 ? round(($completedTasksCount / $totalTasksCount) * 100) : 0;
        $recentProjects = $projects->sortByDesc('created_at')->take(3);

        return view('dashboard.index', [
            'activeProjectsCount' => $activeProjectsCount,
            'totalTasksCount' => $totalTasksCount,
            'completedTasksCount' => $completedTasksCount,
            'completionPercentage' => $completionPercentage,
            'recentProjects' => $recentProjects
        ]);
    }
}
