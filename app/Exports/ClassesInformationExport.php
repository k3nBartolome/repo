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
        // Convert stdClass objects to associative arrays
        $this->data = collect($data)->map(function ($item) {
            return (array) $item;
        });
    }

    public function collection()
    {
        // Format data for currency and percentage
        return $this->data->map(function ($row, $index) {
            $row['Monthly Salary'] = number_format($row['Monthly Salary'], 2); // Format as currency
            $row['ND%-Training'] = $row['ND%-Training'] . '%';
            $row['ND%-Production'] = $row['ND%-Production'] . '%';
            $row['Mid-Shift%-Production'] = $row['Mid-Shift%-Production'] . '%';
            return $row;
        });
    }

    public function headings(): array
    {
        return [
            'NO',
            'HRID',
            'LAST NAME',
            'FIRST NAME',
            'MIDDLE NAME',
            'DATE HIRED',
            'DEPT/LOB/ACCOUNT',
            'TEAM/WAVE',
            'POSITION',
            'BLDG ASSIGNMENT',
            'MONTHLY SALARY',
            'ND%-TRAINING',
            'ND%-PRODUCTION',
            'MID-SHIFT%-PRODUCTION',
            'COMPLEXITY ALLOWANCE(UPON START)',
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
                'color' => ['argb' => 'FFFFFF00'],
            ],
            'font' => [
                'color' => ['argb' => 'FF000000'],
            ],
        ]);

        // Apply currency format to the Monthly Salary column
        $sheet->getStyle('K2:K' . ($sheet->getHighestRow()))->getNumberFormat()->setFormatCode('$#,##0.00');

        return $sheet;
    }
}
