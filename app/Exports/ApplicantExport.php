<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ApplicantExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = collect($data);
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
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
}
