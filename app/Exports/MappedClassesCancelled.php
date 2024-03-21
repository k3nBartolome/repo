<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Collection;

class MappedClassesCancelled implements FromCollection, WithHeadings, WithTitle
{
    protected $data;
    protected $title;

    public function __construct($data,$title)
    {
        $this->data = collect($data);
        $this->title = $title;
    }

    public function collection()
    {
        return $this->data;
    }
    public function title(): string
    {
        return 'Cancelled'; 
    }
    public function headings(): array
    {
        return [
            'ID',
            'Country',
            'Region',
            'Site Name',
            'Program Name',
            'Date Range',
            'Month',
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
            'Cancellation Reason',
            'Status',
            'Category',
            'Type of Hiring',
            'With ERF',
            'ERF Number',
            'Wave Number',
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
            'Created At',
            'Updated At',
          
        ];
    }
}
