<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'success' => true,
            'message' => $this->message ?? 'Success',
            'data' => parent::toArray($request),
        ];
    }
}
