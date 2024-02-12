<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClassHistoryExport implements FromCollection, WithHeadings
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
            'Country',
            'Region',
            'Site',
            'Program',
            'Hiring Week',
            'Externals',
            'Internals',
            'Total Target',
            'Start Date',
            'Original Start Date',
            'Changes',
            'Within Sla',
            'Type of Hiring',
            'Category',
            'With ERF',
            'ERF#',
            'Wave#',
            'Requested By',
            'Notice Days',
            'Notice Weeks',
            'Remarks'

        ];
    }
}
