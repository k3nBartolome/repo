<?php

namespace Database\Seeders;

use App\Models\Classes;
use Illuminate\Database\Seeder;

class ClassesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 1; ++$i) {
            for ($j = 1; $j <= 3; ++$j) {
                for ($k = 1; $k <= 52; ++$k) {
                    $className = Classes::create(['site_id' => $i, 'program_id' => $j, 'date_range_id' => $k, 'total_target' => 0]);
                }
            }
        }
    }
}
