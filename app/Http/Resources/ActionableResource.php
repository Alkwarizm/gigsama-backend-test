<?php

namespace App\Http\Resources;

use App\Models\Actionable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Actionable */
class ActionableResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'description' => $this->description,
            'status' => $this->status,
            'schedule' => $this->schedule,
            'cron_expression' => $this->cron_expression,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'note' => new NoteResource($this->whenLoaded('note')),
        ];
    }
}
