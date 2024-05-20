<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Collection;

class StaffingWeeklyPipe implements FromCollection, WithHeadings,  WithTitle, ShouldAutoSize {
    protected $data;
    protected $title;

    public function __construct( $data, $title ) {
        $this->data = collect( $data );
        $this->title = $title;
    }

    public function collection() {
        return $this->data;
    }

    public function title(): string {
        return 'Weekly Pipe';
    }

    public function headings(): array {
        return [
            'Hiring Week',
            'Site',
            'Program',
            'Program Group',
            'Total Target',
            'Internal',
            'External',
            'Total SU',
            'Fill Rate %',
            'Day 1 SU',
            'Day 1 SU %',
            'Total Hires',
            'Pipeline to Goal %'
        ];
    }
}
