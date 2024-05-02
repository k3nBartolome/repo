<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassShowUp extends Model
{
    protected $table = 'class_show_up';

    protected $fillable = [
        'csu_status',
        'csu_day',
        'csu_updated_by',
        'apn_id',
        'csu_added_by',
        'csu_last_update',
        'csu_added_date',
        'classes_id',
    ];
}
