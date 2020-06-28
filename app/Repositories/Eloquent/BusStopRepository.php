<?php

namespace App\Repositories\Eloquent;

use DB;
use App\BusStop;
use App\Contracts\Repositories\BusStopRepository as BusStopRepositoryContract;

class BusStopRepository extends EloquentRepository implements BusStopRepositoryContract
{
    /**
     * {@inheritdoc}
     */
    public function findNearest(float $sourceLat, float $sourceLong, array $with = []) : ?BusStop
    {
        return $this->model->select([
                    '*',
                    DB::raw(
                        '(
                            6367 * 
                            acos(cos(radians(?)) * cos(radians(lat)) * 
                            cos(radians(`long`) - radians(?)) + sin(radians(?)) * 
                            sin(radians(lat)))
                        ) AS distance'
                    )
                ]
            )
            ->with($with)
            ->setBindings([$sourceLat, $sourceLong, $sourceLat])
            ->orderBy('distance')
            ->first();
    }
}