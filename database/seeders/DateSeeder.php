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
        $start_date = Carbon::parse('2023-12-31');
        $end_date = Carbon::parse('2024-12-28');

        $weeks = [];
        while ($start_date->lte($end_date)) {
            $week_start = $start_date->format('Y-m-d');
            $week_end = $start_date->addDays(6)->format('Y-m-d');
            $month = $start_date->format('F');
            $year = $start_date->format('Y');
            $month_num = $start_date->format('m');
            $quarter = ceil($month_num / 3);

            $weeks[] = [
                'date_range' => Carbon::parse($week_start)->format('M j').' - '.Carbon::parse($week_end)->format('M j'),
                'week_start' => $week_start,
                'week_end' => $week_end,
                'month_num' => $month_num,
                'quarter' => $quarter,
                'month' => $month,
                'year' => $year,
            ];

            $start_date->addDays(1);
        }

        // Remove the conditional check for existing data
        DB::table('date_ranges')->insert($weeks);
        DB::table('date_ranges')->update([
            'date_id' => DB::raw('id'),
        ]);
    }
}
