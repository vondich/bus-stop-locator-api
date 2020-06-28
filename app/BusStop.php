<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BusStop extends Model
{
    /**
     * The buses that belong to the bus stop.
     */
    public function buses()
    {
        return $this->belongsToMany(Bus::class, 'bus_stop_buses')
            ->using(BusStopBus::class)
            ->withPivot([
                'first_arrival_time',
                'last_arrival_time'
            ]);
    }
}
