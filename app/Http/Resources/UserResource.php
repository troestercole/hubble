<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\OrganizationResource;

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
            'roles' => $this->whenLoaded('roles', fn() => $this->roles->pluck('name')),
            'permissions' => $this->when($request->user()?->id === $this->id, fn() => $this->getAllPermissions()->pluck('name')),
            'organization' => $this->when($request->user()?->id === $this->id, fn() => new OrganizationResource($this->organization)),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
