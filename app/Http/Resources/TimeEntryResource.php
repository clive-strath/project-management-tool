<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TimeEntryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'task_id' => $this->task_id,
            'user' => $this->whenLoaded('user'),
            'started_at' => $this->started_at,
            'ended_at' => $this->ended_at,
            'duration' => $this->duration,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
        ];
    }
}
