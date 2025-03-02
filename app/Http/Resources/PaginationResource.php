<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginationResource extends ResourceCollection
{
    protected $pagination;

    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->pagination = [
            'current_page' => $resource->currentPage(),
            'last_page' => $resource->lastPage(),
            'per_page' => $resource->perPage(),
            'total' => $resource->total(),
            'next_page_url' => $resource->nextPageUrl(),
            'prev_page_url' => $resource->previousPageUrl(),
        ];
    }

    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'pagination' => $this->pagination,
        ];
    }
}
