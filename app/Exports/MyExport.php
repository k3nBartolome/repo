<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MyExport
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function export()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add headings
        $headings = [
            'ApplicantId',
            'ApplicationInfoId',
            'DateOfApplication',
            'LastName',
            'FirstName',
            'MiddleName',
            'MobileNo',
            'Site',
            'GenSource',
            'SpecSource',
            'Step',
            'AppStep',
            'PERX_HRID',
            'PERX_Last_Day',
            'PERX_Retract',
            'PERX_NAME',
            'OSS_HRID',
            'OSS_Last_Day',
            'OSS_Retract',
            'OSS_FNAME',
            'OSS_LNAME',
            'OSS_LOB',
            'OSS_SITE',
        ];

        $sheet->fromArray([$headings], null, 'A1');
        
        // Add data
        $row = 2;
        foreach ($this->data as $item) {
            $sheet->fromArray([$item], null, 'A' . $row);
            $row++;
        }

        // Apply styles
        $sheet->getStyle('1')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Create a writer and save the file
        $writer = new Xlsx($spreadsheet);
        $writer->save('filtered_perx_data.xlsx');
    }
}
