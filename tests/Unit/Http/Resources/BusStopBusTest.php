<?php

namespace Tests\Unit\Http\Resources;

use App\BusStopBus;
use Tests\TestCase;
use App\Http\Resources\Bus;
use App\Http\Resources\BusStop;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Resources\BusStopBus as BusStopBusResource;

class BusStopBusTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var BusStopBus
     */
    private $busStopBus;

    /**
     * @var BusStopBusResource
     */
    private $resource;

    public function setUp(): void
    {
        parent::setUp();

        $this->busStopBus = factory(BusStopBus::class)->create();
        $this->resource = new BusStopBusResource($this->busStopBus);
    }

    public function testToArraySuccess()
    {
        $expected = [
            'id' => $this->busStopBus->id,
            'bus_stop_id' => $this->busStopBus->bus_stop_id,
            'bus_id' => $this->busStopBus->bus_id,
            'first_arrival_time' => $this->busStopBus->first_arrival_time,
            'last_arrival_time' => $this->busStopBus->last_arrival_time,
            'bus' => new Bus($this->busStopBus->bus),
            'bus_stop' => new BusStop($this->busStopBus->busStop)
        ];
        $this->assertEquals($expected, $this->resource->jsonSerialize());
    }
}