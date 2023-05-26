<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteSeederAdd extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sites = [
            [
                'name' => 'CLARK',
                'country' => 'Philippines',
            ],
            [
                'name' => 'QUEZON CITY',
                'country' => 'Philippines',
            ],
            [
                'name' => 'BRIDGETOWNE',
                'country' => 'Philippines',
            ],
            [
                'name' => 'MAKATI',
                'country' => 'Philippines',
            ],
            [
                'name' => 'MOA',
                'country' => 'Philippines',
            ],
            [
                'name' => 'SM DAVAO',
                'country' => 'Philippines',
            ],
            [
                'name' => 'SM ROBINSON',
                'country' => 'Philippines',
            ],
            [
                'name' => 'DAVAO DELTA',
                'country' => 'Philippines',
            ],
            [
                'name' => 'DAVAO CENTRAL',
                'country' => 'Philippines',
            ],
            [
                'name' => 'DFC',
                'country' => 'Philippines',
            ],
        ];
        foreach ($sites as $site) {
            DB::table('sites')
                ->where('name', $site['name'])
                ->update(['country' => $site['country']]);
        }
    }
}
