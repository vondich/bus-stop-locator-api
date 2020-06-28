<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Bus extends JsonResource
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
            'code' => $this->code,
            'first_arrival_time' => $this->whenPivotLoaded('bus_stop_buses', function () {
                return $this->pivot->first_arrival_time;
            }),
            'last_arrival_time' => $this->whenPivotLoaded('bus_stop_buses', function () {
                return $this->pivot->last_arrival_time;
            }),
        ];
    }
}
