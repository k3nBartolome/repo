<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    use HasFactory;
    protected $table = 'award';

    protected $fillable = [
        'award_status',
        'remarks',
        'awardee_name',
        'awardee_hrid',
        'date_released',
        'file_name',
        'path',
        'inventory_item_id',
        'processed_by',
        'site_id',
    ];
}
