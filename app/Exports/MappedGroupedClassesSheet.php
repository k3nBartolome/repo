<?php
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MappedGroupedClassesSheet implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize {
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
    }

    public function headings(): array {
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
