<?php

namespace App\Services;

use App\BusStopBus;
use App\Contracts\Repositories\BusRepository;
use App\Contracts\Repositories\BusStopBusRepository;

class RegisterBusForBusStop
{
    private BusRepository $busRepository;
    private BusStopBusRepository $busStopBusRepository;

    public function __construct(
        BusRepository $busRepository,
        BusStopBusRepository $busStopBusRepository
    ) {
        $this->busRepository = $busRepository;
        $this->busStopBusRepository = $busStopBusRepository;
    }

    public function store(array $data) : BusStopBus
    {
        $bus = $this->busRepository->findByBusCode($data['code']);

        // store bus only if it does not exist yet
        if (!$bus) {
            $bus = $this->busRepository->store($data);
        }

        // link bus to bus stop and set arrival information
        $busStopBus = $this->busStopBusRepository->store([
            'bus_stop_id' => $data['bus_stop_id'],
            'bus_id' => $bus->id,
            'first_arrival_time' => $data['first_arrival_time'],
            'last_arrival_time' => $data['last_arrival_time'],
        ]);

        return $busStopBus;
    }
}