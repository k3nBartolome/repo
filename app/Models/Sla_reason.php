<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sla_reason extends Model
{
    use HasFactory;
    protected $fillable = [
        'class_id',
        'reason',
    ];

    public function classes()
    {
        return $this->belongsTo(Classes::class);
    }
}
