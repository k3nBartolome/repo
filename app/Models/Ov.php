<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ov extends Model
{
    use HasFactory;

    protected $table = 'ov'; // Specify the table name

    protected $fillable = [
        'ov_mode',
        'ov_poc',
        'ov_result',
        'ov_status',
        'ov_program',
        'ov_updated_by',
        'apn_id',
        'ov_added_by',
        'ov_last_update',
        'ov_added_date',
    ];
}
