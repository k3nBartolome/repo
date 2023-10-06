<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CopyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sites')->update([
            'site_id' => DB::raw('id'),
        ]);
        DB::table('programs')->update([
            'program_id' => DB::raw('id'),
        ]);
        DB::table('date_ranges')->update([
            'date_id' => DB::raw('id'),
        ]);
    }
}
