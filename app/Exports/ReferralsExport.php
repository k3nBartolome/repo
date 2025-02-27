<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReferralsExport implements FromCollection, WithHeadings, ShouldAutoSize
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
          /*'Id',
            'JobId',
            'UserId', */
            'FirstName',
            'MiddleName',
            'LastName',
            'EmailAddress',
            'ContactNo',
            'TypeOfReferral',
            'Region',
            'Site',
            'ReferrerHRID',
            'ReferrerName',
            'ReferrerSite',
            'ReferrerProgram',
           /*  'CreatedBy', */
            'DateCreated',
         /*    'UpdatedBy', */
            'DateUpdated',
            'Status',
        ];
    }
}
