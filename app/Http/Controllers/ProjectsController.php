<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Project;
use App\Models\User;
use App\Models\Board;
use App\Models\TaskList;

class ProjectsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get projects where user is owner or member with proper eager loading
        $projects = Project::where(function($query) use ($user) {
            $query->whereHas('members', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->orWhere('owner_id', $user->id);
        })
        ->with(['owner', 'members' => function($query) use ($user) {
            $query->where('user_id', $user->id)->withPivot('role');
        }])
        ->withCount('members')
        ->get()
        ->map(function($project) use ($user) {
            // Add user role to each project
            $member = $project->members->first();
            $project->user_role = $member ? $member->pivot->role : ($project->owner_id == $user->id ? 'owner' : null);
            return $project;
        });
        
        return view('projects.index', ['projects' => $projects]);
    }

    public function create()
    {
        // All authenticated users can create projects
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date'
        ]);

        $user = Auth::user();
        
        try {
            // Create the project directly in the database
            $project = Project::create([
                'name' => $request->name,
                'description' => $request->description,
                'owner_id' => $user->id,
                'status' => 'active',
                'due_date' => $request->due_date
            ]);
            
            // Automatically add the creator as a project lead
            $project->addMember($user->id, 'lead');
            
            // Create default board and task lists
            $board = $project->boards()->create([
                'name' => 'Main Board',
                'type' => 'kanban',
                'position' => 0
            ]);
            
            // Create default task lists matching board columns
            $taskLists = [
                ['name' => 'Backlog', 'position' => 0],
                ['name' => 'In Progress', 'position' => 1],
                ['name' => 'Done', 'position' => 2]
            ];
            
            foreach ($taskLists as $list) {
                $board->taskLists()->create($list);
            }
            
            return redirect()->route('projects.index')
                ->with('success', 'Project created successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to create project: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to create project: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $user = Auth::user();
        $project = Project::with(['owner', 'members', 'boards.taskLists.tasks'])
            ->findOrFail($id);
        
        // Check if user has access to the project
        if (!$project->isProjectMember($user->id) && $project->owner_id !== $user->id) {
            return redirect()->route('projects.index')
                ->with('error', 'You do not have permission to view this project');
        }
        
        // Calculate statistics
        $allTasks = collect();
        foreach ($project->boards as $board) {
            foreach ($board->taskLists as $taskList) {
                $allTasks = $allTasks->merge($taskList->tasks);
            }
        }
        
        $totalTasks = $allTasks->count();
        $completedTasks = $allTasks->where('status', 'completed')->count();
        $inProgressTasks = $allTasks->where('status', 'in_progress')->count();
        $teamMembers = $project->members->count() + 1; // +1 for owner
        
        return view('projects.show', [
            'project' => $project,
            'totalTasks' => $totalTasks,
            'completedTasks' => $completedTasks,
            'inProgressTasks' => $inProgressTasks,
            'teamMembers' => $teamMembers
        ]);
    }

    public function members($id)
    {
        $project = Project::findOrFail($id);
        
        // Check if user is a project lead or owner
        if (!$project->isProjectLead(Auth::id()) && $project->owner_id !== Auth::id()) {
            return redirect()->route('projects.show', $id)
                ->with('error', 'You do not have permission to manage project members');
        }

        // Get all members including pivot data
        $members = $project->members()->withPivot('role', 'joined_at')->get();
        
        // Get owner and add to members list if not already included
        $owner = $project->owner;
        $memberIds = $members->pluck('id')->toArray();
        if ($owner && !in_array($owner->id, $memberIds)) {
            // Create a fake pivot for owner
            $owner->setRelation('pivot', (object)[
                'role' => 'owner',
                'joined_at' => $project->created_at
            ]);
            $members->push($owner);
        }
        
        // Get available users (exclude current members and owner)
        $excludedIds = $members->pluck('id')->toArray();
        if ($project->owner_id) {
            $excludedIds[] = $project->owner_id;
        }
        $availableUsers = User::whereNotIn('id', array_unique($excludedIds))->get();

        return view('projects.members', [
            'project' => $project,
            'members' => $members,
            'availableUsers' => $availableUsers
        ]);
    }

    public function addMember(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        
        // Check if user is a project lead or owner
        if (!$project->isProjectLead(Auth::id()) && $project->owner_id !== Auth::id()) {
            return redirect()->route('projects.show', $id)
                ->with('error', 'You do not have permission to add members');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:lead,member'
        ]);

        $project->addMember($request->user_id, $request->role);

        return redirect()->route('projects.members', $id)
            ->with('success', 'Member added successfully!');
    }

    public function removeMember($projectId, $userId)
    {
        $project = Project::findOrFail($projectId);
        
        // Check if user is a project lead or owner
        if (!$project->isProjectLead(Auth::id()) && $project->owner_id !== Auth::id()) {
            return redirect()->route('projects.show', $projectId)
                ->with('error', 'You do not have permission to remove members');
        }

        // Prevent removing the project owner
        if ($project->owner_id == $userId) {
            return redirect()->route('projects.members', $projectId)
                ->with('error', 'Cannot remove the project owner');
        }

        $project->removeMember($userId);

        return redirect()->route('projects.members', $projectId)
            ->with('success', 'Member removed successfully!');
    }
}
