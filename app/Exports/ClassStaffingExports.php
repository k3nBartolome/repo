<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClassStaffingExports implements FromCollection, WithHeadings, ShouldAutoSize
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
            'Month',
            'Week',
            'Site',
            'Program',
            'Total Target',
            'Internal',
            'External',
            'total',
            'Cap Starts',
            'day 1',
            'day 2',
            'day 3',
            'day 4',
            'day 5',
            'Filled',
            'Open',
            'Classes',
        ];
    }
}
