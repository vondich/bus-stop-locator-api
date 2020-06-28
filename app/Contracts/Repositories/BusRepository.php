<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Model;

interface BusRepository extends BaseRepository
{
    /**
     * Checks if the given bus code is already linked to the bus stop id
     * 
     * @param int $busStopId
     * @param string $busCode
     * 
     * @return bool
     */
    public function existsForBusStopId(int $busStopId, string $busCode) : bool;

    /**
     * Finds a bus by its code
     * 
     *@param string $busCode
     * 
     * @return Model|null
     */
    public function findByBusCode(string $busCode) : ?Model;
}
