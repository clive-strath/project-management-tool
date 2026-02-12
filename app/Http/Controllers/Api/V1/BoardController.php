<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BoardResource;
use App\Models\Project;
use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use App\Events\BoardUpdated;

class BoardController extends Controller
{
    public function index(Project $project)
    {
        Gate::authorize('view', $project);

        return BoardResource::collection($project->boards);
    }

    public function store(Request $request, Project $project)
    {
        Gate::authorize('update', $project);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:kanban,list',
            'position' => 'nullable|integer',
        ]);

        $board = $project->boards()->create($validated);

        return new BoardResource($board);
    }

    public function show(Board $board)
    {
        Gate::authorize('view', $board);

        return new BoardResource($board->load('taskLists.tasks'));
    }

    public function update(Request $request, Board $board)
    {
        Gate::authorize('update', $board);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|in:kanban,list',
            'position' => 'sometimes|required|integer',
        ]);

        $board->update($validated);

        broadcast(new BoardUpdated($board))->toOthers();

        return new BoardResource($board);
    }

    public function destroy(Board $board)
    {
        Gate::authorize('delete', $board);

        $board->delete();

        return response()->noContent();
    }
}
