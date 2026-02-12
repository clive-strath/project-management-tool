<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TaskAttachmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'task_id' => $this->task_id,
            'user_id' => $this->user_id,
            'filename' => $this->filename,
            'url' => Storage::disk('public')->url($this->path),
            'size' => $this->size,
            'mime_type' => $this->mime_type,
            'created_at' => $this->created_at,
        ];
    }
}
