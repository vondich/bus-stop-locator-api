<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusStopsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $busStops = [
            [
                'name' => 'Telok Ayer Stn Exit A',
                'code' => '03041',
                'lat' => 1.2819753,
                'long' => 103.8465511,
            ],
            [
                'name' => 'Raffles Pl Stn Exit F',
                'code' => '03031',
                'lat' => 1.2822404,
                'long' => 103.8487854,
            ],
            [
                'name' => 'One Raffles Quay',
                'code' => '03059',
                'lat' => 1.28112,
                'long' => 103.8492613,
            ],
            [
                'name' => 'OCBC Ctr',
                'code' => '05319',
                'lat' => 1.2844925,
                'long' => 103.846945,
            ],
            [
                'name' => 'Opp Hong Lim Cplx',
                'code'  => '05199',
                'lat' => 1.2845117,
                'long' => 103.8445076,
            ],
            [
                'name' => 'Opp SO Sofitel',
                'code' => '03071',
                'lat' => 1.2800246,
                'long' => 103.8472317,
            ],
        ];

        foreach ($busStops as $busStop) {
            DB::table('bus_stops')->insert($busStop);
        }
    }
}
