<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BusStopBus extends Pivot
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bus_stop_buses';

    /**
     * The bus stop associated with this model
     */
    public function busStop()
    {
        return $this->hasOne(BusStop::class, 'id', 'bus_stop_id');
    }

    /**
     * The bus associated with this model
     */
    public function bus()
    {
        return $this->hasOne(Bus::class, 'id', 'bus_id');
    }
}
