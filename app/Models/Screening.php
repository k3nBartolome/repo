<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Screening extends Model
{
    use HasFactory;

    protected $table = 'screening'; // Specify the table name

    protected $fillable = [
        'sc_scheduled_by',
        'sc_processed_by',
        'sc_recruiter_notes',
        'sc_scheduled_timeslot',
        'sc_time_assigned',
        'sc_status',
        'sc_start',
        'sc_end',
        'sc_aht',
        'sc_updated_by',
        'apn_id',
        'sc_remarks',
        'sc_added_by',
        'sc_last_update',
        'sc_added_date',
    ];
}
