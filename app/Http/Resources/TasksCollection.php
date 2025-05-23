<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TasksCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,  
            'meta' => [
                'total' => $this->resource->total(),
                'current_page' => $this->resource->currentPage(),
                'per_page' => $this->resource->perPage()
            ]          
        ];
    }

    
}
