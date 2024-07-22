<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    // Specify the database connection name (if it's different from the default)
    protected $connection = 'sqlsrv';

    // Specify the table name
    protected $table = 'SMART_RECRUIT.PH_TA.dbo.APP_DB';

    // Specify the primary key column name
    protected $primaryKey = 'SR_ID';

    // Disable the timestamps (created_at and updated_at)
    public $timestamps = false;

    // Define the fillable properties if you want to allow mass assignment
    protected $fillable = [
        'SR_ID',
        'ApplicationDate',
        'FirstName',
        'LastName',
        'MiddleName',
        'Suffix',
        'Email',
        'CellphoneNumber',
        'BirthDate',
        'CivilStatus',
        'MotherFirstName',
        'MotherLastName',
        'MotherMiddleName',
        'C_Country',
        'C_Region',
        'C_Province',
        'C_City',
        'City_Address',
        'ExpectedSalary',
        'Step',
        'GeneralSource',
        'SpecificSource',
        'GeneralStatus',
        'SpecificStatus',
        'PositionApplyingFor',
        'SiteApplied',
        'KO_Score',
        'GQ1',
        'GQ2',
        'WQ',
        'SVA',
        'HighestEducationalAttainment',
        'SchoolName',
        'SchoolAddress',
        'Course',
        'SchoolStartDate',
        'SchoolEndDate',
        'GraduationDate',
        'ReferralID',
        'id',
        'ApplicantAppicationsId',
        'ApplicantId',
        'Pronunciation',
        'Grammar',
        'Fluency',
        'Lexis',
        'CCC',
        'Tone',
        'PreEmps',
        'EmpBkgrnd',
        'SchedAmenability',
        'EducAttainment',
        'HealthCond',
        'Availability',
        'SitePref',
        'CustService',
        'Sales',
        'StressTol',
        'Integrity',
        'Achievement',
        'ProblemSolving',
        'IntervieweeId',
        'ApplicantApplicationId',
        'Interviewer',
        'LIRemarks',
        'RIRemarks',
        'BCIRemarks',
        'ProfilingRemarks',
        'Class',
        'OVRemarks',
        'OVRecruiter',
        'OVDate',
        'ImmediateSupervisorHRID',
        'ImmediateSupervisorName',
        'WorkSetup',
        'CompanyName',
        'WorkExpType',
        'LastWorkingDate',
        'AdapterIndustry',
        'Position',
        'Salary',
        'WorkTenureMonths',
        'ReasonForLeaving',
        'AccountType',
        'ExperienceType',
        'WorkType',
        'TotalWorkExp',
        'TotalWorkExpBPO',
        'TotalWorkExpNonBPO',
        'Segment',
    ];
}
