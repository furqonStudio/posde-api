<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Store\StoreSimpleResource;
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
            'store_id' => $this->store_id,
            'store' => new StoreSimpleResource($this->whenLoaded('store')),
            // 'email_verified_at' => $this->email_verified_at ? $this->email_verified_at->toDateTimeString() : null,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
