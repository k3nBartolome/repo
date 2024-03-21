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

    public function __construct($mappedGroupedClassesWeek, $mappedGroupedClasses, $mappedClassesMoved, $mappedClassesCancelled, $mappedClassesSla)
    {
        $this->mappedGroupedClassesWeek = collect($mappedGroupedClassesWeek);
        $this->mappedGroupedClasses = collect($mappedGroupedClasses);
        $this->mappedClassesMoved = collect($mappedClassesMoved);
        $this->mappedClassesCancelled = collect($mappedClassesCancelled);
        $this->mappedClassesSla = collect($mappedClassesSla);
    }

    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new MappedGroupedClassesWeekSheet($this->mappedGroupedClassesWeek);
        $sheets[] = new MappedGroupedClassesSheet($this->mappedGroupedClasses);
        $sheets[] = new MappedClassesMoved($this->mappedClassesMoved);
        $sheets[] = new MappedClassesCancelled($this->mappedClassesCancelled);
        $sheets[] = new MappedClassesSla($this->mappedClassesSla);

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
