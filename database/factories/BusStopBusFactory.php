<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Bus;
use App\BusStop;
use App\BusStopBus;
use Faker\Generator as Faker;

$factory->define(BusStopBus::class, function (Faker $faker) {
    return [
        'bus_stop_id' => function () {
            return factory(BusStop::class)->create()->id;
        },
        'bus_id' => function () {
            return factory(Bus::class)->create()->id;
        },
        'first_arrival_time' => '00:00',
        'last_arrival_time' => '23:59',
    ];
});
