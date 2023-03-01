<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SitesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create the first record
        DB::table('sites')->insert([
            'name' => 'CLARK',
            'description' => 'CLARK',
            'site_director' => '',
            'region' => 'LUZON 1',
            'is_active' => 1,
            'created_by' => 1,
            'updated_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // create the second record
        DB::table('sites')->insert([
            'name' => 'QUEZON CITY',
            'description' => 'QUEZON CITY',
            'site_director' => null,
            'region' => 'LUZON 1',
            'is_active' => 1,
            'created_by' => 1,
            'updated_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // create the third record
        DB::table('sites')->insert([
            'name' => 'BRIDGETOWNE',
            'description' => 'BRIDGETOWNE',
            'site_director' => null,
            'region' => 'LUZON 2',
            'is_active' => 1,
            'created_by' => 1,
            'updated_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // create the fourth record
        DB::table('sites')->insert([
            'name' => 'MAKATI',
            'description' => 'MAKATI',
            'site_director' => null,
            'region' => 'LUZON 2',
            'is_active' => 1,
            'created_by' => 1,
            'updated_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // create the fifth record
        DB::table('sites')->insert([
            'name' => 'MOA',
            'description' => 'MOA',
            'site_director' => null,
            'region' => 'LUZON 2',
            'is_active' => true,
            'created_by' => 1,
            'updated_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('sites')->insert([
            'name' => 'SM DAVAO',
            'description' => 'SM DAVAO',
            'site_director' => '',
            'region' => 'DAVAO',
            'is_active' => 1,
            'created_by' => 1,
            'updated_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // create the second record
        DB::table('sites')->insert([
            'name' => 'SM ROBINSON',
            'description' => 'SM ROBINSON',
            'site_director' => null,
            'region' => 'DAVAO',
            'is_active' => 1,
            'created_by' => 1,
            'updated_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // create the third record
        DB::table('sites')->insert([
            'name' => 'DAVAO DELTA',
            'description' => 'DAVAO DELTA',
            'site_director' => null,
            'region' => 'DAVAO',
            'is_active' => 1,
            'created_by' => 1,
            'updated_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // create the fourth record
        DB::table('sites')->insert([
            'name' => 'DAVAO CENTRAL',
            'description' => 'DAVAO CENTRAL',
            'site_director' => null,
            'region' => 'DAVAO',
            'is_active' => 1,
            'created_by' => 1,
            'updated_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // create the fifth record
        DB::table('sites')->insert([
            'name' => 'DFC',
            'description' => 'DFC',
            'site_director' => null,
            'region' => 'DAVAO',
            'is_active' => 1,
            'created_by' => 1,
            'updated_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
