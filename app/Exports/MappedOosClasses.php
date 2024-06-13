<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Collection;

class MappedOosClasses implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize
{
    protected $data;
    protected $title;

    public function __construct($data, $title)
    {
        $this->data = collect($data);
        $this->title = $title;
    }

    public function collection()
    {
        return $this->data;
    }

    public function title(): string
    {
        return 'Out of SLA Increase in Demand';
        // Specify the title of the worksheet
    }

    public function headings(): array
    {
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
