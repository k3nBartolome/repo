<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationInfo extends Model
{
    protected $table = 'application_info';

    protected $fillable = [
        'apn_segment',
        'apn_shifter_type',
        'apn_mode_of_application',
        'apn_actual_mode_of_application',
        'apn_lead_type',
        'apn_site',
        'apn_ko',
        'apn_word_quiz',
        'apn_sva',
        'apn_typing_test',
        'apn_typing_test_accuracy',
        'apn_typing_test_speed',
        'apn_oot_non_oot',
        'apn_gen_source',
        'apn_specific_source',
        'apn_name_of_event',
        'apn_diser_name',
        'apn_employee_referrer_name',
        'apn_referrer_hrid',
        'apn_referrer_account',
        'apn_applicant_referrer_name',
        'apn_updated_by',
        'ai_id',
        'apn_added_by',
        'apn_added_date',
        'apn_last_update',
        'apn_application_date',
        'apn_application_week_ending',
        'apn_application_month',
        'apn_leads_added_date',
        'apn_status',
    ];
}
