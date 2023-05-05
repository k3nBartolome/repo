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
            'week_start' => $this->week_start,
            'week_end' => $this->week_end,
            'month' => $this->month,
            'month_num' => $this->month_num,
            'year' => $this->year,
        ];
    }
}
