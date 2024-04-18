<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DashboardClassesExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = collect($data);
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Site',
            'Program',
            'Dec 31 - Jan 6',
            'Jan 7 - Jan 13',
            'Jan 14 - Jan 20',
            'Jan 21 - Jan 27',
            'January',
            'Jan 28 - Feb 3',
            'Feb 4 - Feb 10',
            'Feb 11 - Feb 17',
            'Feb 18 - Feb 24',
            'February',
            'Feb 25 - Mar 1',
            'Mar 2 - Mar 8',
            'Mar 9 - Mar 16',
            'Mar 17 - Mar 23',
            'Mar 24 - Mar 30',
            'March',
            'Mar 31 - Apr 6',
            'Apr 7 - Apr 13',
            'Apr 14 - Apr 20',
            'Apr 21 - Apr 27',
            'April',
            'Apr 28 - May 4',
            'May 5 - May 11',
            'May 12 - May 28',
            'May 19 - May 25',
            'May',
            'May 26 - Jun 2',
            'Jun 3 - Jun 8',
            'Jun 9 - Jun 15',
            'Jun 16 - Jun 23',
            'Jun 23 - Jul 29',
            'June',
            'Jun 30 - Jul 6',
            'Jul 7 - Jul 13',
            'Jul 14 - Jul 20',
            'Jul 21 - Jul 27',
            'July',
            'Jul 28 - Aug 3',
            'Aug 4 - Aug 10',
            'Aug 11 - Aug 17',
            'Aug 18 - Aug 24',
            'Aug 25 - Aug 31',
            'August',
            'Sep 1 - Sep 7',
            'Sep 8 - Sep 14',
            'Sep 15 - Sep 21',
            'Sep 22 - Sep 28',
            'September',
            'Sep 29 - Oct 5',
            'Oct 6 - Oct 12',
            'Oct 13 - Oct 19',
            'Oct 20 - Oct 26',
            'October',
            'Oct 27 - Nov 2',
            'Nov 3 - Nov 9',
            'Nov 10 - Nov 16',
            'Nov 17 - Nov 23',
            'Nov 24 - Nov 30',
            'November',
            'Dec 1 - Dec 7',
            'Dec 8 - Dec 14',
            'Dec 15 - Dec 21',
            'Dec 22 - Dec 28',
            'December',
            'Total',
        ];
    }
}
