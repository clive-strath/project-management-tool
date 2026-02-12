<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'due_date' => $this->due_date,
            'owner' => $this->whenLoaded('owner'),
            'members' => $this->whenLoaded('members'),
            'boards' => BoardResource::collection($this->whenLoaded('boards')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
