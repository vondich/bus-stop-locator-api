<?php

namespace App\Contracts\Repositories;

use App\Bus;
use App\BusStop;
use App\BusStopBus;

interface BusStopRepository extends BaseRepository
{
    /**
     * Finds the nearest bus stop from the specifed source coordinates
     * 
     * @param float $sourceLat
     * @param float $sourceLong
     * 
     * @return BusStop|null
     */
    public function findNearest(float $sourceLat, float $sourceLong, array $with = []) : ?BusStop;
}
