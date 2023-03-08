<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DateRangeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'date_range' => $this->date_range,
            'month' => $this->month,
            'year' => $this->year,
        ];
    }
}
