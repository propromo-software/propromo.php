<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'success' => true,
            'total_users' => $this->collection->count(),
            'users' => $this->collection->map(fn($user) => new UserResource($user)), // Wrap with UserResource
        ];
    }

    /**
     * Add meta information to the response.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        return [
            'meta' => [
                'api_version' => '1.0',
                'request_time' => now()->toDateTimeString(),
            ]
        ];
    }
}
