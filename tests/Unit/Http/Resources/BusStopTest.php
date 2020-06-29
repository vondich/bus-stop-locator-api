<?php

namespace Tests\Unit\Http\Resources;

use App\Bus;
use App\BusStop;
use Tests\TestCase;
use App\Http\Resources\Bus as BusResource;
use App\Http\Resources\BusStop as BusStopResource;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BusStopTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var BusStop
     */
    private $busStop;

    /**
     * @var BusStopResource
     */
    private $resource;

    public function setUp(): void
    {
        parent::setUp();

        $this->busStop = factory(BusStop::class)->create();
        $this->resource = new BusStopResource($this->busStop);
    }

    public function testToArraySuccessWithoutBuses()
    {
        $expected = [
            'id' => $this->busStop->id,
            'name' => $this->busStop->name,
            'code' => $this->busStop->code,
            'lat'  => $this->busStop->lat,
            'long' => $this->busStop->long
        ];
        $this->assertEquals($expected, $this->resource->jsonSerialize());
    }

    public function testToArraySuccessWithBuses()
    {
        // create a bus and link it to our bus stop
        $bus = factory(Bus::class)->create();
        $this->busStop->buses()->attach($bus->id, [
            'first_arrival_time' => '00:00',
            'last_arrival_time' => '23:59',
        ]);
        $this->busStop->load(['buses']);

        $expected = [
            'id' => $this->busStop->id,
            'name' => $this->busStop->name,
            'code' => $this->busStop->code,
            'lat'  => $this->busStop->lat,
            'long' => $this->busStop->long,
            'buses' => BusResource::collection($this->busStop->buses)
        ];

        $actual = $this->resource->jsonSerialize();
        
        $this->assertEquals($expected, $actual);
        $this->assertCount(1, $actual['buses']);
    }
}