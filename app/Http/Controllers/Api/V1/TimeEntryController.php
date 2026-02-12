<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TimeEntryResource;
use App\Models\Task;
use App\Models\TimeEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class TimeEntryController extends Controller
{
    public function index(Task $task)
    {
        Gate::authorize('view', $task);

        return TimeEntryResource::collection($task->timeEntries->load('user'));
    }

    public function store(Request $request, Task $task)
    {
        Gate::authorize('view', $task);

        $validated = $request->validate([
            'started_at' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $entry = $task->timeEntries()->create($validated + ['user_id' => $request->user()->id]);

        return new TimeEntryResource($entry);
    }

    public function show(TimeEntry $timeEntry)
    {
        Gate::authorize('view', $timeEntry);

        return new TimeEntryResource($timeEntry->load('user'));
    }

    public function update(Request $request, TimeEntry $timeEntry)
    {
        Gate::authorize('update', $timeEntry);

        $validated = $request->validate([
            'started_at' => 'sometimes|required|date',
            'ended_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        if (isset($validated['ended_at']) && isset($validated['started_at'])) {
            $start = Carbon::parse($validated['started_at']);
            $end = Carbon::parse($validated['ended_at']);
            $validated['duration'] = $end->diffInMinutes($start);
        } elseif (isset($validated['ended_at'])) {
             $start = Carbon::parse($timeEntry->started_at);
             $end = Carbon::parse($validated['ended_at']);
             $validated['duration'] = $end->diffInMinutes($start);
        }

        $timeEntry->update($validated);

        return new TimeEntryResource($timeEntry);
    }

    public function destroy(TimeEntry $timeEntry)
    {
        Gate::authorize('delete', $timeEntry);

        $timeEntry->delete();

        return response()->noContent();
    }
}
