<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DashboardClassesExportWeek implements WithMultipleSheets
 {
    protected $mappedGroupedClassesWeek;
    protected $mappedGroupedClasses;
    protected $mappedExternalClasses;
    protected $mappedInternalClasses;
    protected $mappedClassesMoved;
    protected $mappedClassesCancelled;
    protected $mappedClassesSla;
    protected $worksheetNames;

    public function __construct( $mappedGroupedClassesWeek, $mappedGroupedClasses, $mappedExternalClasses, $mappedInternalClasses, $mappedClassesMoved, $mappedClassesCancelled, $mappedClassesSla, $worksheetNames )
 {
        $this->mappedGroupedClassesWeek = collect( $mappedGroupedClassesWeek );
        $this->mappedGroupedClasses = collect( $mappedGroupedClasses );
        $this->mappedExternalClasses = collect( $mappedExternalClasses );
        $this->mappedInternalClasses = collect( $mappedInternalClasses );
        $this->mappedClassesMoved = collect( $mappedClassesMoved );
        $this->mappedClassesCancelled = collect( $mappedClassesCancelled );
        $this->mappedClassesSla = collect( $mappedClassesSla );
        $this->worksheetNames = $worksheetNames;
    }

    public function sheets(): array
 {
        $sheets = [];

        $sheets[] = new MappedGroupedClassesWeekSheet( $this->mappedGroupedClassesWeek, $this->worksheetNames[ 0 ] );
        $sheets[] = new MappedGroupedClassesSheet( $this->mappedGroupedClasses, $this->worksheetNames[ 1 ] );
        $sheets[] = new MappedExternalClasses( $this->mappedExternalClasses, $this->worksheetNames[ 2 ] );
        $sheets[] = new MappedInternalClasses( $this->mappedInternalClasses, $this->worksheetNames[ 3 ] );
        $sheets[] = new MappedClassesMoved( $this->mappedClassesMoved, $this->worksheetNames[ 4 ] );
        $sheets[] = new MappedClassesCancelled( $this->mappedClassesCancelled, $this->worksheetNames[ 5 ] );
        $sheets[] = new MappedClassesSla( $this->mappedClassesSla, $this->worksheetNames[ 6 ] );

        return $sheets;
    }

    public function map( $mappedGroupedClassesWeek ): array
 {
        return [
            'Mapped Grouped Classes Week' => $this->mappedGroupedClassesWeek,
            'Mapped Site Classes' => $this->mappedGroupedClasses,
            'Mapped External Classes' => $this->mappedExternalClasses,
            'Mapped Internal Classes' => $this->mappedInternalClasses,
            'Mapped Classes Moved' => $this->mappedClassesMoved,
            'Mapped Classes Cancelled' => $this->mappedClassesCancelled,
            'Mapped Classes Sla' => $this->mappedClassesSla,
        ];
    }
}
