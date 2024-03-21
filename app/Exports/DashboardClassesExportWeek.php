<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DashboardClassesExportWeek implements WithMultipleSheets
{
    protected $mappedGroupedClassesWeek;
    protected $mappedGroupedClasses;
    protected $mappedClassesMoved;
    protected $mappedClassesCancelled;
    protected $mappedClassesSla;
    protected $worksheetNames;

    public function __construct($mappedGroupedClassesWeek, $mappedGroupedClasses, $mappedClassesMoved, $mappedClassesCancelled, $mappedClassesSla, $worksheetNames)
    {
        $this->mappedGroupedClassesWeek = collect($mappedGroupedClassesWeek);
        $this->mappedGroupedClasses = collect($mappedGroupedClasses);
        $this->mappedClassesMoved = collect($mappedClassesMoved);
        $this->mappedClassesCancelled = collect($mappedClassesCancelled);
        $this->mappedClassesSla = collect($mappedClassesSla);
        $this->worksheetNames = $worksheetNames;
    }

    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new MappedGroupedClassesWeekSheet($this->mappedGroupedClassesWeek,$this->worksheetNames[0]);
        $sheets[] = new MappedGroupedClassesSheet($this->mappedGroupedClasses,$this->worksheetNames[1]);
        $sheets[] = new MappedClassesMoved($this->mappedClassesMoved,$this->worksheetNames[2]);
        $sheets[] = new MappedClassesCancelled($this->mappedClassesCancelled,$this->worksheetNames[3]);
        $sheets[] = new MappedClassesSla($this->mappedClassesSla,$this->worksheetNames[3]);

        return $sheets;
    }

    public function map($mappedGroupedClassesWeek): array
    {
        return [
            'Mapped Grouped Classes Week' => $this->mappedGroupedClassesWeek,
            'Mapped Site Classes' => $this->mappedGroupedClasses,
            'Mapped Classes Moved' => $this->mappedClassesMoved,
            'Mapped Classes Cancelled' => $this->mappedClassesCancelled,
            'Mapped Classes Sla' => $this->mappedClassesSla,
        ];
    }
}
