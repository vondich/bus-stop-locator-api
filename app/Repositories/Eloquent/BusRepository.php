<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\BusRepository as BusRepositoryContract;
use Illuminate\Database\Eloquent\Model;

class BusRepository extends EloquentRepository implements BusRepositoryContract
{
    /**
     * {@inheritdoc}
     */
    public function existsForBusStopId(int $busStopId, string $busCode) : bool
    {
        return $this->model
            ->where('code', $busCode)
            ->whereHas('busStops', function ($query) use ($busStopId) {
                $query->where('bus_stop_id', $busStopId);
            })
            ->exists();
    }

    /**
     * {@inheritdoc}
     */
    public function findByBusCode(string $busCode) : ?Model
    {
        return $this->model
            ->where('code', $busCode)
            ->first();
    }
}
