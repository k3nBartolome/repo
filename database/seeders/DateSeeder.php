<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $start_date = Carbon::parse('2023-01-01');
        $end_date = Carbon::parse('2023-12-30');

        $weeks = [];
        while ($start_date->lte($end_date)) {
            $week_start = $start_date->format('Y-m-d');
            $week_end = $start_date->addDays(6)->format('Y-m-d');
            $month = $start_date->format('F');
            $year = $start_date->format('Y');

            $weeks[] = [
                'date_range' => Carbon::parse($week_start)->format('F j').' - '.Carbon::parse($week_end)->format('F j'),
        'week_start' => $week_start,
        'week_end' => $week_end,
        'month' => $month,
        'year' => $year,
    ];

            $start_date->addDays(1);
        }

        DB::table('date_tables')->insert($weeks);
    }
}
