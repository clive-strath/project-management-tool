<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'task_list_id' => $this->task_list_id,
            'title' => $this->title,
            'description' => $this->description,
            'assignee' => $this->whenLoaded('assignee'),
            'priority' => $this->priority,
            'status' => $this->status,
            'due_date' => $this->due_date,
            'position' => $this->position,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
