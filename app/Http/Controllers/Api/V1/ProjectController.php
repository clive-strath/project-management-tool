<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = $request->user()->role?->slug === 'admin'
            ? Project::all()
            : Project::where('owner_id', $request->user()->id)
                ->orWhereHas('members', function ($query) use ($request) {
                    $query->where('user_id', $request->user()->id);
                })
                ->get();

        return ProjectResource::collection($projects);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Project::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        $project = $request->user()->ownedProjects()->create($validated + ['status' => 'active']);

        return new ProjectResource($project);
    }

    public function show(Project $project)
    {
        Gate::authorize('view', $project);

        return new ProjectResource($project->load(['owner', 'members', 'boards']));
    }

    public function update(Request $request, Project $project)
    {
        Gate::authorize('update', $project);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|required|in:active,completed,archived',
            'due_date' => 'nullable|date',
        ]);

        $project->update($validated);

        return new ProjectResource($project);
    }

    public function destroy(Project $project)
    {
        Gate::authorize('delete', $project);

        $project->delete();

        return response()->noContent();
    }
}
