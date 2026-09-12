<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'project_id' => $this->project_id,
            'created_by' => $this->created_by,
            'approved_by' => $this->approved_by,
            'category' => $this->category,
            'title' => $this->title,
            'description' => $this->description,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'expense_date' => $this->expense_date?->toDateString(),
            'vendor' => $this->vendor,
            'payment_method' => $this->payment_method,
            'receipt_path' => $this->receipt_path,
            'status' => $this->status,
            'approved_at' => $this->approved_at?->toISOString(),
            'notes' => $this->notes,
            'project' => new ProjectResource($this->whenLoaded('project')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
