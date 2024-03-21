<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DashboardClassesExportWeek implements WithMultipleSheets
{
    protected $mappedGroupedClassesWeek;
    protected $mappedB2Classes;
    protected $mappedClassesMoved;
    protected $mappedClassesCancelled;

    public function __construct($mappedGroupedClassesWeek, $mappedB2Classes, $mappedClassesMoved, $mappedClassesCancelled)
    {
        $this->mappedGroupedClassesWeek = collect($mappedGroupedClassesWeek);
        $this->mappedB2Classes = collect($mappedB2Classes);
        $this->mappedClassesMoved = collect($mappedClassesMoved);
        $this->mappedClassesCancelled = collect($mappedClassesCancelled);
    }

    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new MappedGroupedClassesWeekSheet($this->mappedGroupedClassesWeek);
        $sheets[] = new MappedB2ClassesSheet($this->mappedB2Classes);
        $sheets[] = new MappedClassesMoved($this->mappedClassesMoved);
        $sheets[] = new MappedClassesCancelled($this->mappedClassesCancelled);

        return $sheets;
    }

    public function map($mappedGroupedClassesWeek): array
    {
        return [
            'Mapped Grouped Classes Week' => $this->mappedGroupedClassesWeek,
            'Mapped B2 Classes' => $this->mappedB2Classes,
            'Mapped Classes Moved' => $this->mappedClassesMoved,
            'Mapped Classes Cancelled' => $this->mappedClassesCancelled,
        ];
    }
}

