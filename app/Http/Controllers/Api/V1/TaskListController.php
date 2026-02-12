<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskListResource;
use App\Models\Board;
use App\Models\TaskList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaskListController extends Controller
{
    public function index(Board $board)
    {
        Gate::authorize('view', $board);

        return TaskListResource::collection($board->taskLists);
    }

    public function store(Request $request, Board $board)
    {
        Gate::authorize('update', $board);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|integer',
        ]);

        $list = $board->taskLists()->create($validated);

        return new TaskListResource($list);
    }

    public function show(TaskList $taskList)
    {
        Gate::authorize('view', $taskList->board);

        return new TaskListResource($taskList->load('tasks'));
    }

    public function update(Request $request, TaskList $taskList)
    {
        Gate::authorize('update', $taskList->board);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'position' => 'sometimes|required|integer',
        ]);

        $taskList->update($validated);

        return new TaskListResource($taskList);
    }

    public function destroy(TaskList $taskList)
    {
        Gate::authorize('delete', $taskList->board);

        $taskList->delete();

        return response()->noContent();
    }
}
