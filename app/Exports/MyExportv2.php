<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MyExportv2 implements FromCollection, WithHeadings, ShouldAutoSize
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
        'DateOfApplication',
        'LastName',
        'FirstName',
        'MiddleName',
        'MobileNumber',
        'Region',
        'Site',
        'SpecSource',
        'GeneralSource',
        'Step',
        'GenStatus',
        'SpecStatus',
        'ReferrerHRID',
        'ReferrerFirstName',
        'ReferrerMiddleName',
        'ReferrerLastName',
        'ReferrerName',
        'DeclaredReferrerName',
        'DeclaredReferrerId'
        ];
    }
}
