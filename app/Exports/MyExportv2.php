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
            'Email',
            'Region',
            'Site',
            'GeneralSource',
            'SpecSource',
            'Step',
            'GenStatus',
            'SpecStatus',
            'ReferredLastName',
            'ReferredFirstName',
            'ReferredMiddleName',
            'ReferrerHRID',
            'ReferrerName',
            'DeclaredReferrerName',
            'DeclaredReferrerId',
            'JobTitle',
        ];
    }
}
