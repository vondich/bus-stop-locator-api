<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    protected $fillable = [
        'code'
    ];

    /**
     * The bus stops that are associated to this bus.
     */
    public function busStops()
    {
        return $this->belongsToMany(BusStop::class, 'bus_stop_buses')
            ->using(BusStopBus::class)
            ->withPivot([
                'id',
                'first_arrival_time',
                'last_arrival_time'
            ]);
    }
}
