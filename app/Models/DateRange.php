<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DateRange extends Model
{
    protected $fillable = [
        'date_range',
        'month',
        'year',
    ];

    public function classes()
    {
        return $this->hasMany(Classes::class);
    }
}
