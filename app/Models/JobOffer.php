<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobOffer extends Model
{
    protected $table = 'job_offer';

    protected $fillable = [
        'jo_poc',
        'jo_date',
        'jo_month',
        'jo_lead_time',
        'jo_start_date',
        'jo_start_month',
        'jo_status',
        'jo_type',
        'jo_position',
        'jo_remarks',
        'jo_updated_by',
        'apn_id',
        'jo_added_by',
        'jo_last_update',
        'jo_added_date',
    ];

    // Optionally, you can define relationships or additional methods here
}
