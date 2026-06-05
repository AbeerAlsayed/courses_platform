<?php

namespace App\Domains\Auth\Http\Resources;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstructorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'     => $this->id,
            'user'   => new UserResource($this->whenLoaded('user')),
            'bio'    => $this->bio,
            'status' => $this->status,
        ];
    }
}
