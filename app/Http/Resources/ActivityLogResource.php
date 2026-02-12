<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => $this->whenLoaded('user'),
            'subject_id' => $this->subject_id,
            'subject_type' => $this->subject_type,
            'action' => $this->action,
            'metadata' => $this->metadata,
            'created_at' => $this->created_at,
        ];
    }
}
