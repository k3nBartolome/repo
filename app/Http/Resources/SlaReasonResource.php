<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SlaReasonResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'reason' => $this->reason,
            'class_id' => $this->class_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
