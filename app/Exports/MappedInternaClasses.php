<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Collection;

class MappedInternalClasses implements FromCollection, WithHeadings, WithTitle {
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
        return 'Site Summary';
        // Specify the title of the worksheet
    }

    public function headings(): array {
        // Define your headings for MappedB2Classes sheet
        return [
            'Site Name',
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'Jun',
            'Jul',
            'Aug',
            'Sep',
            'Oct',
            'Nov',
            'Dec',
            'Total'

        ];
    }
}
