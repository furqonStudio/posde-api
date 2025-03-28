<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Category\CategorySimpleResource;
use App\Http\Resources\User\UserSimpleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'image' => asset('storage/' . $this->image),
            'name' => $this->name,
            'category' => new CategorySimpleResource($this->whenLoaded('category')),
            'user' => new UserSimpleResource($this->whenLoaded('user')),
            'price' => $this->price,
            'stock' => $this->stock,
            "description" => $this->description,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
