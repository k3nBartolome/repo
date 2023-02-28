<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DateRangesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('date_ranges')->insert([
            ['range' => 'Jan 1-Jan 7'],
            ['range' => 'Jan 8-Jan 14'],
            ['range' => 'Jan 15-Jan 21'],
            ['range' => 'Jan 22-Jan 28'],
            ['range' => 'Jan 29-Feb 4'],
            ['range' => 'Feb 5-Feb 11'],
            ['range' => 'Feb 12-Feb 18'],
            ['range' => 'Feb 19-Feb 25'],
            ['range' => 'Feb 26-Mar 4'],
            ['range' => 'Mar 5-Mar 11'],
            ['range' => 'Mar 12-Mar 18'],
            ['range' => 'Mar 19-Mar 25'],
            ['range' => 'Mar 26-Apr 1'],
            ['range' => 'April 2-Apr 8'],
            ['range' => 'Apr 9-Feb 15'],
            ['range' => 'Apr 16-Apr 22'],
            ['range' => 'Apr 23-Apr 29'],
            ['range' => 'Apr 30-May 6'],
            ['range' => 'May 7-May 13'],
            ['range' => 'May 14-Jan 20'],
            ['range' => 'May 21-May 27'],
            ['range' => 'May 28-Jun 3'],
            ['range' => 'jun 4-Feb 10'],
            ['range' => 'Jun 11-Jun 17'],
            ['range' => 'Jun 18-Jun 24'],
            ['range' => 'Jun 25-Jul 1'],
            ['range' => 'Jul 2-Jul 8'],
            ['range' => 'Jul 8-Jul 15'],
            ['range' => 'Jul 16-Jul 22'],
            ['range' => 'Jul 23-Jul 29'],
            ['range' => 'July 30-Aug 5'],
            ['range' => 'Aug 6-Aug 12'],
            ['range' => 'Aug 13-Aug 19'],
            ['range' => 'Aug 20-Aug 26'],
            ['range' => 'Aug 26-Sep 2'],
            ['range' => 'Sep 3-Sep 19'],
            ['range' => 'Sep 10-Sep 16'],
            ['range' => 'Sep 17-Sep 23'],
            ['range' => 'Sep 24-Sep 30'],
            ['range' => 'Oct 1-Oct 7'],
            ['range' => 'Oct 8-Oct 14'],
            ['range' => 'Oct 15-Oct 21'],
            ['range' => 'Oct 22-Oct 28'],
            ['range' => 'Oct 29-Nov 4'],
            ['range' => 'Nov 5-Nov 11'],
            ['range' => 'Nov 12-Nov 18'],
            ['range' => 'Nov 19-Nov 25'],
            ['range' => 'Nov 26-Dec 2'],
            ['range' => 'Dec 3-Dec 9'],
            ['range' => 'Dec 10-Dec 16'],
            ['range' => 'Dec 17-Dec 23'],
            ['range' => 'Dec 18-Dec 30'],
        ]);
    }
}
