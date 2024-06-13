<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ClassHistoryExport implements FromCollection, WithHeadings, ShouldAutoSize
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

            'ID',
            'Country',
            'Region',
            'Site',
            'Program',
            'Hiring Week',
            'External Target',
            'Internal Target',
            'Total Target',
            'Within SLA',
            'Condition',
            'Original Start Date',
            'Changes',
            'Agreed Start Date',
            'WFM Date Requested',
            'Notice Weeks',
            'Notice Days',
            'Pipeline Utilized',
            'Remarks',
            'Status',
            'Category',
            'Type of Hiring',
            'With ERF',
            'ERF Number',
            'Wave No',
            'TA',
            'WF',
            'TR',
            'CL',
            'OP',
            'Approved By',
            'Cancelled By',
            'Requested By',
            'Created By',
            'Updated By',
            'Approved Date',
            'Cancelled Date',
            'Created Date',
            'Updated Date',


        ];
    }
}
