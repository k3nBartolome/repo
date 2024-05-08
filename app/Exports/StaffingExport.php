<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class StaffingExport implements WithMultipleSheets
{
    protected $weeklyPipe;
    protected $wtd;
    protected $ytd;
    protected $worksheetNames;

    public function __construct($weeklyPipe, $wtd,  $ytd, $worksheetNames)
    {
         $this->weeklyPipe = collect($weeklyPipe);
        $this->wtd = collect($wtd); 
        $this->ytd = collect($ytd);
        $this->worksheetNames = $worksheetNames;
    }

    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new StaffingWeeklyPipe($this->weeklyPipe, $this->worksheetNames[0]);
        $sheets[] = new StaffingWTD($this->wtd, $this->worksheetNames[1]); 
        $sheets[] = new StaffingYTD($this->ytd, $this->worksheetNames[2]);
        return $sheets;
    }

    public function map($mappedGroupedClassesWeek): array
    {
        return [
            'Weekly Pipe' => $this->weeklyPipe,
            'WTD' => $this->wtd, 
            'YTD' => $this->ytd,

        ];
    }
}
