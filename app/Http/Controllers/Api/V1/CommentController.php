<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskCommentResource;
use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use App\Events\CommentAdded;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index(Task $task)
    {
        Gate::authorize('view', $task);

        return TaskCommentResource::collection($task->comments->load('user'));
    }

    public function store(Request $request, Task $task)
    {
         Gate::authorize('view', $task);

        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $comment = $task->comments()->create($validated + ['user_id' => $request->user()->id]);

        broadcast(new CommentAdded($comment))->toOthers();

        return new TaskCommentResource($comment->load('user'));
    }

    public function destroy(TaskComment $comment)
    {
        if (Auth::id() !== $comment->user_id && Auth::user()->role?->slug !== 'admin') {
            abort(403);
        }

        $comment->delete();

        return response()->noContent();
    }
}
