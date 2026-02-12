<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskAttachmentResource;
use App\Models\Task;
use App\Models\TaskAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class TaskAttachmentController extends Controller
{
    public function index(Task $task)
    {
        Gate::authorize('view', $task);

        return TaskAttachmentResource::collection($task->attachments);
    }

    public function store(Request $request, Task $task)
    {
        Gate::authorize('update', $task);

        $request->validate([
            'file' => 'required|file|max:10240', // 10MB limit
        ]);

        $file = $request->file('file');
        $path = $file->store('attachments/' . $task->id, 'public');

        $attachment = $task->attachments()->create([
            'user_id' => $request->user()->id,
            'filename' => $file->getClientOriginalName(),
            'path' => $path,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ]);

        return new TaskAttachmentResource($attachment);
    }

    public function download(TaskAttachment $attachment)
    {
        Gate::authorize('view', $attachment->task);

        return Storage::disk('public')->download($attachment->path, $attachment->filename);
    }

    public function destroy(TaskAttachment $attachment)
    {
        Gate::authorize('update', $attachment->task);

        Storage::disk('public')->delete($attachment->path);
        $attachment->delete();

        return response()->noContent();
    }
}
