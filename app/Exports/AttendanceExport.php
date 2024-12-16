<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendanceExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $applicants;

    public function __construct($applicants)
    {
        $this->applicants = $applicants;
    }

    public function collection()
    {
        return collect($this->applicants);
    }

    public function headings(): array
    {
        return [
            'Site',
            'Last Name',
            'First Name',
            'Middle Name',
            'Email',
            'Contact Number',
            'In',
        ];
    }
}