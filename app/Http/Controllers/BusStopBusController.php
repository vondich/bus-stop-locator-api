<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bus\RegisterBusRequest;
use App\Http\Resources\BusStopBus as BusStopBusResource;
use App\Services\RegisterBusForBusStop;
use DB;

class BusStopBusController extends Controller
{
    /**
     * Registers a new bus for a bus stop
     *
     * @param  \App\Http\Requests\Bus\RegisterBusRequest $registerBusRequest
     * @param   App\Services\RegisterBusForBusStop $registerBusForBusStopService
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RegisterBusRequest $registerBusRequest, RegisterBusForBusStop $registerBusForBusStopService)
    {
        $busStopBus = DB::transaction(function () use (
            $registerBusRequest,
            $registerBusForBusStopService
        ) {
            return $registerBusForBusStopService->store($registerBusRequest->all());
        });
        
        return new BusStopBusResource($busStopBus);
    }
}