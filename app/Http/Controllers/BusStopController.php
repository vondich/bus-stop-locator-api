<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Contracts\Repositories\BusStopRepository;
use App\Http\Resources\BusStop as BusStopResource;
use App\Http\Requests\BusStop\GetNearestBusStopRequest;

class BusStopController extends Controller
{
    /** @var \App\Contracts\Repositories\BusStopRepository */
    private $busStopRepository;

    public function __construct(BusStopRepository $busStopRepository)
    {
        $this->busStopRepository = $busStopRepository;
    }

    /**
     * Shows the nearest bus stop
     *
     * @param  \App\Http\Requests\BusStop\GetNearestBusStopRequest $getNearestBusStopRequest
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showNearest(GetNearestBusStopRequest $getNearestBusStopRequest)
    {
        $busStop = $this->busStopRepository
            ->findNearest(
                $getNearestBusStopRequest->source_lat,
                $getNearestBusStopRequest->source_long,
                ['buses']
            );
        
        return new BusStopResource($busStop);
    }
}