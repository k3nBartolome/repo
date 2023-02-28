<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DateRange extends Model
{
    protected $fillable = [
        'range',
    ];

    public function classes()
    {
        return $this->belongsTo(Classes::class);
    }
}
