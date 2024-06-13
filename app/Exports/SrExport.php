<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SrExport implements FromCollection, WithHeadings, ShouldAutoSize
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
            'SR ID',
            'Date',
            'Last Name',
            'First Name',
            'Middle Name',
            'Site',
            'Mobile No.',
            'GenSource',
            'SpecSource',
            'Step',
            'AppStep',
        ];
    }
}
