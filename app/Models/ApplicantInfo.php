<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApplicantInfo extends Model
{
    protected $table = 'applicant_info';

    protected $fillable = [
        'ai_sr_id',
        'ai_first_name',
        'ai_middle_name',
        'ai_last_name',
        'ai_suffix',
        'ai_email_address',
        'ai_contact_number',
        'ai_marital_status',
        'ai_city_municipality',
        'ai_complete_address',
        'ai_gender',
        'ai_age',
        'ai_birthdate',
        'ai_hrid',
        'ai_highest_educational_attainment',
        'ai_last_school_attended',
        'ai_year_graduated',
        'ai_course_strand',
        'ai_leads_added_by',
        'ai_leads_added_date',
        'ai_last_update_date',
        'ai_last_updated_by',
    ];
}
