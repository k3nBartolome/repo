<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InitialInterview extends Model
{
    use HasFactory;

    protected $table = 'initial_interview';

    protected $fillable = [
        'ii_ofac',
        'ii_final_step',
        'ii_final_status',
        'ii_status_remarks',
        'ii_account',
        'ii_recruiter_name',
        'ii_profile_summary',
        'ii_recruiter_notes',
        'ii_cs_vertical',
        'ii_sales_vertical',
        'ii_tech_vertical',
        'ii_total_mos',
        'ii_recently_mos',
        'ii_tenure_in_last_job',
        'ii_tenure_in_last_job_month',
        'ii_interviewer',
        'ii_time_started',
        'ii_time_ended',
        'ii_aht',
        'ii_cs_score',
        'ii_srp_score',
        'ii_status',
        'ii_remarks',
        'ii_updated_by',
        'apn_id',
        'ii_added_by',
        'ii_last_update',
        'ii_added_date',
        'ii_expected_salary',
    ];
}
