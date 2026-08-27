<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskAttachmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'task_id'         => $this->task_id,
            'original_name'   => $this->original_name,
            'size'            => $this->size,
            'size_for_humans' => $this->size_for_humans,
            'url'             => $this->url,
            'user'            => new UserResource($this->whenLoaded('user')),
            'created_at'      => $this->created_at?->toISOString(),
        ];
    }
}
