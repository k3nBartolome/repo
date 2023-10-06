<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DateRange extends Model
{
    protected $fillable = [
        'date_range',
        'week_start',
        'week_end',
        'month_num',
        'month',
        'year',
        'date_id',
    ];

    public function classes()
    {
        return $this->hasMany(Classes::class);
    }
}
