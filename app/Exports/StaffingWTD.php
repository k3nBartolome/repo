<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class StaffingWTD implements FromCollection, WithHeadings, WithTitle
 {
    protected $data;
    protected $title;

    public function __construct( $data, $title )
 {
        $this->data = collect( $data );
        $this->title = $title;
    }

    public function collection()
 {
        return $this->data;
    }

    public function title(): string
 {
        return 'MTD';
    }

    public function headings(): array
 {
        return [
            'Month',
            'Hiring Week',
            'Total Target',
            'Overall Pipeline',
            'Pipeline To Goal',
            'Total Internals',
            'Total Externals',
            'For JO',
            'For Testing',
            'Internal',
            'External',
            'Total SU',
            'Fill Rate %',
            'Day 1 SU',
            'Day 1 SU %',
        ];
    }
}
