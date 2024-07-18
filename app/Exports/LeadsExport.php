<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LeadsExport implements FromCollection, WithHeadings, ShouldAutoSize
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
            'ApplicantId',
            'ApplicationInfoId',
            'DateOfApplication',
            'LastName',
            'FirstName',
            'MiddleName',
            'MobileNo',
            'Site',
            'GenSource',
            'SpecSource',
            'Step',
            'AppStep',
            'PERX_HRID',
            'PERX_Last_Day',
            'PERX_Retract',
            'PERX_NAME',
            'OSS_HRID',
            'OSS_Last_Day',
            'OSS_Retract',
            'OSS_FNAME',
            'OSS_LNAME',
            'OSS_LOB',
            'OSS_SITE',
        ];
    }
}
