<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicantSiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sites = [
            ['name' => 'BRIDGETOWNE', 'description' => 'Bridgetowne'],
            ['name' => 'CLARK', 'description' => 'Clark'],
            ['name' => 'DVCEN', 'description' => 'Davao Central'],
            ['name' => 'DVDEL', 'description' => 'Davao Del Sur'],
            ['name' => 'DVSM', 'description' => 'Davao SM'],
            ['name' => 'MAKATI', 'description' => 'Makati'],
            ['name' => 'MOA', 'description' => 'Mall of Asia'],
            ['name' => 'QCPAN', 'description' => 'Quezon City Panorama'],
        ];

        foreach ($sites as $site) {
            DB::table('applicant_site')->insert([
                'name' => $site['name'],
                'description' => $site['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
