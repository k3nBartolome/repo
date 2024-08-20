<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClassesInformationExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
            'HRID',
            'Last Name',
            'First Name',
            'Middle Name',
            'Date Hired',
            'Account',
            'Wave',
            'Position',
            'BLDG Assignment',
            'Monthly Salary',
            'ND%-Training',
            'ND%-Production',
            'Mid-Shift%-Production',
            'Complexity Allowance',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:J1')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'FFADD8E6'],
            ],
            'font' => [
                'color' => ['argb' => 'FFFFFFFF'],
            ],
        ]);
        $sheet->getStyle('K1:O1')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'FFFFEFD5'],
            ],
            'font' => [
                'color' => ['argb' => 'FFFFFFFF'],
            ],
        ]);

        return $sheet;
    }
}
