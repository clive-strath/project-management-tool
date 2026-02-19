<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\TaskList;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use App\Events\TaskCreated;
use App\Events\TaskUpdated;
use App\Events\TaskDeleted;
use App\Events\TaskMoved;

class TaskController extends Controller
{
    public function index(TaskList $taskList)
    {
        Gate::authorize('view', $taskList->board);

        return TaskResource::collection($taskList->tasks);
    }

    public function store(Request $request, TaskList $taskList)
    {
        Gate::authorize('update', $taskList->board);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assignee_id' => 'nullable|exists:users,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:backlog,in_progress,completed',
            'due_date' => 'nullable|date',
            'position' => 'nullable|integer',
        ]);

        $task = $taskList->tasks()->create($validated);

        broadcast(new TaskCreated($task))->toOthers();

        return new TaskResource($task);
    }

    public function show(Task $task)
    {
        Gate::authorize('view', $task);

        return new TaskResource($task->load(['assignee', 'comments', 'attachments']));
    }

    public function update(Request $request, Task $task)
    {
        Gate::authorize('update', $task);

        $oldListId = $task->task_list_id;

        $validated = $request->validate([
            'task_list_id' => 'sometimes|required|exists:task_lists,id',
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'assignee_id' => 'nullable|exists:users,id',
            'priority' => 'sometimes|required|in:low,medium,high,urgent',
            'status' => 'sometimes|required|in:todo,in_progress,review,done',
            'due_date' => 'nullable|date',
            'position' => 'sometimes|required|integer',
        ]);

        $task->update($validated);

        if (isset($validated['task_list_id']) && $validated['task_list_id'] != $oldListId) {
            broadcast(new TaskMoved($task, $oldListId))->toOthers();
        } else {
            broadcast(new TaskUpdated($task))->toOthers();
        }

        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        Gate::authorize('delete', $task);

        $projectId = $task->taskList->board->project_id;
        $taskId = $task->id;

        $task->delete();

        broadcast(new TaskDeleted($projectId, $taskId))->toOthers();

        return response()->noContent();
    }
}
