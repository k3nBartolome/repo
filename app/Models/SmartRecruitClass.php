<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmartRecruitClass extends Model
{
    protected $connection = 'sqlsrv_linked2';
    protected $table = 'SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2.dbo.Class';
    protected $primaryKey = 'ClassId';
    public $timestamps = false;
    protected $fillable = [
        'Team', 'Site', 'Lob', 'ImmediateSupervisorHRID', 'ImmediateSupervisorName',
        'WorkSetup', 'Status', 'StartDate', 'EndDate', 'HireDate', 'TargetHeadcount',
        'OfferTarget', 'OfferCategoryDoc', 'RequiredProgramSpecific', 'ProgramSpecificId',
        'BasicPayTraining', 'BasicPayProduction', 'NightDifferentialTraining',
        'NightDifferentialProduction', 'BonusTraining', 'BonusProduction',
        'IsActive', 'CreatedBy', 'CreatedDate', 'UpdatedBy', 'UpdatedDate'
    ];
}

