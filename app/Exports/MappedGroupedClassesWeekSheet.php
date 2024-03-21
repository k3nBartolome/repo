<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Collection;
class MappedGroupedClassesWeekSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $data;
    protected $title;

    public function __construct($data,$title)
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
        return 'Hiring Summary'; // Specify the title of the worksheet
    }
    public function headings(): array
    {
        // Define your headings for MappedB2Classes sheet
        return [
            'Site',
            'Program',
            'Jan 1 - Jan 7',
            'Jan 8 - Jan 14',
            'Jan 15 - Jan 21',
            'Jan 22 - Jan 28',
            'January',
            'Jan 29 - Feb 4',
            'Feb 5 - Feb 11',
            'Feb 12 - Feb 18',
            'Feb 19 - Feb 25',
            'February',
            'Feb 26 - Mar 4',
            'Mar 5 - Mar 11',
            'Mar 12 - Mar 18',
            'Mar 19 - Mar 25',
            'March',
            'Mar 26 - Apr 1',
            'Apr 2 - Apr 8',
            'Apr 9 - Apr 15',
            'Apr 16 - Apr 22',
            'Apr 23 - Apr 29',
            'April',
            'Apr 30 - May 6',
            'May 7 - May 13',
            'May 14 - May 20',
            'May 21 - May 27',
            'May',
            'May 28 - Jun 3',
            'Jun 4 - Jun 10',
            'Jun 11 - Jun 17',
            'Jun 18 - Jun 24',
            'June',
            'Jun 25 - Jul 1',
            'Jul 2 - Jul 8',
            'Jul 9 - Jul 15',
            'Jul 16 - Jul 22',
            'Jul 23 - Jul 29',
            'July',
            'Jul 30 - Aug 5',
            'Aug 6 - Aug 12',
            'Aug 13 - Aug 19',
            'Aug 20 - Aug 26',
            'Aug 27 - Sep 2',
            'August',
            'Sep 3 - Sep 9',
            'Sep 10 - Sep 16',
            'Sep 17 - Sep 23',
            'Sep 24 - Sep 30',
            'September',
            'Oct 1 - Oct 7',
            'Oct 8 - Oct 14',
            'Oct 15 - Oct 21',
            'Oct 22 - Oct 28',
            'October',
            'Oct 29 - Nov 4',
            'Nov 5 - Nov 11',
            'Nov 12 - Nov 18',
            'Nov 19 - Nov 25',
            'November',
            'Nov 26 - Dec 2',
            'Dec 3 - Dec 9',
            'Dec 10 - Dec 16',
            'Dec 17 - Dec 23',
            'Dec 24 - Dec 30',
            'December',
            'Total',
        ];
    }
}
