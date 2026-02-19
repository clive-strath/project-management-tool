<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\Board;

class BoardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get user's projects with their roles
        $userProjects = Project::where(function($query) use ($user) {
            $query->whereHas('members', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->orWhere('owner_id', $user->id);
        })
        ->with(['owner', 'members' => function($query) use ($user) {
            $query->where('user_id', $user->id)->withPivot('role');
        }])
        ->get()
        ->map(function($project) use ($user) {
            $member = $project->members->first();
            $project->user_role = $member ? $member->pivot->role : ($project->owner_id == $user->id ? 'owner' : null);
            return $project;
        });
        
        // Get all tasks from user's projects with proper eager loading
        $projectIds = $userProjects->pluck('id');
        $tasks = \App\Models\Task::whereHas('taskList.board', function($query) use ($projectIds) {
            $query->whereIn('project_id', $projectIds);
        })
        ->with(['assignee', 'taskList.board.project'])
        ->orderBy('position')
        ->get();
        
        return view('board.index', [
            'tasks' => $tasks,
            'projects' => $userProjects,
            'user' => $user
        ]);
    }

    public function createTask(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'project_id' => 'required|exists:projects,id',
            'assignee_id' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date'
        ]);

        $user = Auth::user();
        
        // Verify user has access to the project
        $project = Project::findOrFail($request->project_id);
        
        // Only project owners and leads can create tasks
        if ($project->owner_id !== $user->id && !$project->isProjectLead($user->id)) {
            return response()->json(['success' => false, 'message' => 'Only project leads can create tasks'], 403);
        }
        
        // Verify user has access to the project (for viewing)
        if (!$project->isProjectMember($user->id) && $project->owner_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        // Get or create default board for project
        $board = $project->boards()->first();
        if (!$board) {
            $board = $project->boards()->create([
                'name' => 'Main Board',
                'type' => 'kanban',
                'position' => 0
            ]);
            
            // Create default task lists
            $taskLists = [
                ['name' => 'Backlog', 'position' => 0],
                ['name' => 'In Progress', 'position' => 1],
                ['name' => 'Done', 'position' => 2]
            ];
            
            foreach ($taskLists as $list) {
                $board->taskLists()->create($list);
            }
        }
        
        // Get the backlog task list
        $backlogList = $board->taskLists()->where('name', 'Backlog')->first();
        if (!$backlogList) {
            $backlogList = $board->taskLists()->create(['name' => 'Backlog', 'position' => 0]);
        }
        
        // Create task directly in database
        $task = Task::create([
            'task_list_id' => $backlogList->id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'backlog',
            'priority' => $request->priority,
            'assignee_id' => $request->assignee_id,
            'due_date' => $request->due_date,
            'position' => 0
        ]);
        
        return response()->json([
            'success' => true, 
            'task' => $task->load(['assignee', 'taskList.board.project'])
        ]);
    }

    public function updateTaskStatus(Request $request, $taskId)
    {
        $request->validate([
            'status' => 'required|in:backlog,in_progress,completed'
        ]);

        $user = Auth::user();
        $task = Task::findOrFail($taskId);
        
        // Verify user has access to the task's project
        $project = $task->taskList->board->project;
        if (!$project->isProjectMember($user->id) && $project->owner_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        // If marking as completed, only allow if user is the assignee OR is project lead/owner
        if ($request->status === 'completed') {
            if ($task->assignee_id !== $user->id && $project->owner_id !== $user->id && !$project->isProjectLead($user->id)) {
                return response()->json(['success' => false, 'message' => 'You can only mark your own tasks as completed'], 403);
            }
        }
        
        // Project leads/owners can change status to any value
        // Regular members can only change their assigned tasks to completed
        if ($request->status !== 'completed' && $project->owner_id !== $user->id && !$project->isProjectLead($user->id)) {
            return response()->json(['success' => false, 'message' => 'Only project leads can change task status'], 403);
        }
        
        // Update task status and move to appropriate task list
        $board = $project->boards()->first();
        $statusToTaskList = [
            'backlog' => 'Backlog',
            'in_progress' => 'In Progress',
            'completed' => 'Done'
        ];
        
        $targetListName = $statusToTaskList[$request->status];
        $targetList = $board->taskLists()->where('name', $targetListName)->first();
        
        if ($targetList) {
            $task->update([
                'status' => $request->status,
                'task_list_id' => $targetList->id
            ]);
        } else {
            $task->update(['status' => $request->status]);
        }
        
        return response()->json([
            'success' => true, 
            'task' => $task->load(['assignee', 'taskList.board.project'])
        ]);
    }

    public function getTaskDetails($taskId)
    {
        $user = Auth::user();
        $task = Task::with(['assignee', 'comments.user', 'attachments', 'taskList.board.project'])
            ->findOrFail($taskId);
        
        // Verify user has access to the task's project
        $project = $task->taskList->board->project;
        if (!$project->isProjectMember($user->id) && $project->owner_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        return response()->json([
            'success' => true,
            'task' => $task,
            'comments' => $task->comments,
            'attachments' => $task->attachments
        ]);
    }
}
