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
            'id'               => $this->id,
            'name'             => $this->name,
            'description'      => $this->description,
            'status'           => $this->status,
            'start_date'       => $this->start_date,
            'end_date'         => $this->end_date,
            'budget'           => $this->budget,
            'category'         => new CategoryResource($this->whenLoaded('category')),
            'category_id'      => $this->category_id,
            'assigned_user_id' => $this->assigned_user_id,
            'assigned_user'    => new UserResource($this->whenLoaded('assignedUser')),
            'members'          => UserResource::collection($this->whenLoaded('members')),
            'tasks'            => TaskResource::collection($this->whenLoaded('tasks')),
            'created_at'       => $this->created_at?->toISOString(),
            'updated_at'       => $this->updated_at?->toISOString(),
        ];
    }
}
