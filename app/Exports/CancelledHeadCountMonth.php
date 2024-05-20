<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Collection;

class CancelledHeadCountMonth implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize {
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
        return 'MTD Per Site Cancelled';
    }

    public function headings(): array
 {

        return [
            'Site Name',
            'HC',
            'Pipeline Offered',
            'Average Notice Weeks',
            'Program Group'

        ];
    }
}
