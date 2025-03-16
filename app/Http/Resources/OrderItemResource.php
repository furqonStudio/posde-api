<?php

namespace App\Http\Resources;

use App\Http\Resources\Product\ProductSimpleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'subtotal' => $this->subtotal,
            'product' => new ProductSimpleResource($this->whenLoaded('product')),
        ];
        return parent::toArray($request);
    }
}
