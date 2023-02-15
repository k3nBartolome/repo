<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Classes;
class Sla_reason extends Model
{
    use HasFactory;
    protected $fillable = [
        'reason',
        'created_by',
        'updated_by'
    ];
    public function classes(){
        return $this->belongsTo(Classes::class);
    }
}
