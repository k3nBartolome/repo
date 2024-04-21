<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobOffer extends Model
{
    use HasFactory;

    protected $table = 'job_offer'; // Specify the table name

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
}
