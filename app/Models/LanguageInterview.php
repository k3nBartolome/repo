<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LanguageInterview extends Model
{
    protected $table = 'language_interview';

    protected $fillable = [
        'li_started',
        'li_end',
        'li_aht',
        'li_date',
        'li_month',
        'li_week',
        'li_pron_accent',
        'li_grammar',
        'li_fluency',
        'li_lexis',
        'li_comprehension',
        'li_tone',
        'li_step_score',
        'li_updated_by',
        'apn_id',
        'li_status',
        'li_remarks',
        'li_added_by',
        'li_last_update',
        'li_added_date',
    ];
}
