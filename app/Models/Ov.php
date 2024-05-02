<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ov extends Model
{
    protected $table = 'ov';

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

    // Optionally, you can define relationships or additional methods here
}
