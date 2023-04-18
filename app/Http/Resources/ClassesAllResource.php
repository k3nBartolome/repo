<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClassesAllResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'classes' => $this->resource,
        ];
    }
}
