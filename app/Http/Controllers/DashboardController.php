<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $token = $user->createToken('app-token')->plainTextToken;
        
        try {
            // Fetch data from API
            $projectsResponse = Http::withToken($token)
                ->get(config('app.url') . '/api/v1/projects');
            
            $tasksResponse = Http::withToken($token)
                ->get(config('app.url') . '/api/v1/tasks');

            $projects = $projectsResponse->successful() ? $projectsResponse->json('data', []) : [];
            $tasks = $tasksResponse->successful() ? $tasksResponse->json('data', []) : collect($tasksResponse->json('data', []));

            // Calculate statistics
            $activeProjectsCount = collect($projects)->where('status', 'active')->count();
            $totalTasksCount = $tasks->count();
            $completedTasksCount = $tasks->where('status', 'completed')->count();
            $completionPercentage = $totalTasksCount > 0 ? round(($completedTasksCount / $totalTasksCount) * 100) : 0;
            $recentProjects = collect($projects)->take(3);

            return view('dashboard.index', [
                'activeProjectsCount' => $activeProjectsCount,
                'totalTasksCount' => $totalTasksCount,
                'completedTasksCount' => $completedTasksCount,
                'completionPercentage' => $completionPercentage,
                'recentProjects' => $recentProjects
            ]);
        } catch (\Exception $e) {
            // Fallback to mock data if API fails
            return view('dashboard.index', [
                'activeProjectsCount' => 0,
                'totalTasksCount' => 0,
                'completedTasksCount' => 0,
                'completionPercentage' => 0,
                'recentProjects' => []
            ]);
        }
    }
}
