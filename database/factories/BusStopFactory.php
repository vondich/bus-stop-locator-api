<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\BusStop;
use Faker\Generator as Faker;

$factory->define(BusStop::class, function (Faker $faker) {
    return [
        'name' => 'Serangoon Int',
        'code' => '992029',
        'lat' => 39.3416740000000000,
        'long' => 92.50175200000000,
    ];
});
