<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'auth_type' => $this->auth_type, // Ensure consistency if used in DB
            'created_at' => $this->created_at?->toDateTimeString(), // Standardized format
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
