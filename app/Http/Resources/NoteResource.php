<?php

namespace App\Http\Resources;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Note */
class NoteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'doctor' => UserResource::make($this->whenLoaded('doctor')),
            'patient' => UserResource::make($this->whenLoaded('patient')),
            'actionables' => ActionableResource::collection($this->whenLoaded('actionables')),
        ];
    }
}
