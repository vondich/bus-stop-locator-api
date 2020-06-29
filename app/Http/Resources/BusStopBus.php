<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BusStopBus extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'bus_stop_id' => $this->bus_stop_id,
            'bus_id' => $this->bus_id,
            'first_arrival_time' => $this->first_arrival_time,
            'last_arrival_time' => $this->last_arrival_time,
            'bus' => new Bus($this->bus),
            'bus_stop' => new BusStop($this->busStop)
        ];
    }
}
