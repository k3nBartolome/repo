<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmartRecruitData extends Model
{
    protected $table = 'SMART_RECRUIT_DATA';

    protected $fillable = [
        'ApplicantId', 'ApplicationInfoId', 'DateOfApplication', 'LastName',
        'FirstName', 'MiddleName', 'MobileNo', 'Site', 'GenSource', 'SpecSource',
        'Status', 'QueueDate', 'Interviewer', 'LOB', 'RescheduleDate', 'Step',
        'AppStep', 'ApplicationStepStatusId', 'WordQuiz', 'SVA', 'Address',
        'Municipality', 'Province', 'last_update_date'
    ];
}
