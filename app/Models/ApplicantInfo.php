<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantInfo extends Model
{
    use HasFactory;

    protected $table = 'applicant_info'; // Specify the table name

    protected $fillable = [
        'ai_sr_id',
        'ai_application_date',
        'ai_application_week_ending',
        'ai_application_month',
        'ai_first_name',
        'ai_middle_name',
        'ai_last_name',
        'ai_suffix',
        'ai_email_address',
        'ai_contact_number',
        'ai_marital_status',
        'ai_city_municipality',
        'ai_complete_address',
        'ai_course',
        'ai_gender',
        'ai_age',
        'ai_is_legal_age',
        'ai_birthdate',
        'ai_hrid',
        'ai_highest_educational_attainment',
        'ai_last_school_attended',
        'ai_year_graduated',
        'ai_course_strand',
        'ai_updated_by',
        'ai_last_update',
        'ai_added_by',
        'ai_added_date',
        'ai_company_name',
        'ai_last_working_date',
        'ai_work_handled',
        'ai_previous_current_salary',
        'ai_company_type',
    ];
}
