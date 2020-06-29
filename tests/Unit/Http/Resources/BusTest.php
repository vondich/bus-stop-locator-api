<?php

namespace Tests\Unit\Http\Resources;

use App\Bus;
use App\BusStop;
use Tests\TestCase;
use App\Http\Resources\Bus as BusResource;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BusTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var Bus
     */
    private $bus;

    /**
     * @var BusResource
     */
    private $resource;

    public function setUp(): void
    {
        parent::setUp();

        $this->bus = factory(Bus::class)->create();
        $this->resource = new BusResource($this->bus);
    }

    public function testToArraySuccessNoPivotLoaded()
    {
        $expected = [
            'id' => $this->bus->id,
            'code' => $this->bus->code,
        ];
        $this->assertEquals($expected, $this->resource->jsonSerialize());
    }

    public function testToArraySuccessWithPivotLoaded()
    {
        // create a bus stop and link it to our bus
        $busStop = factory(BusStop::class)->create();
        $busStop->buses()->attach($this->bus->id, [
            'first_arrival_time' => '00:00',
            'last_arrival_time' => '23:59',
        ]);
        $busStop->load(['buses']);
        $bus = $busStop->buses->first();

        $resource = new BusResource($bus);

        $expected = [
            'id' => $this->bus->id,
            'code' => $this->bus->code,
            'first_arrival_time' => '00:00',
            'last_arrival_time' => '23:59',
        ];
        $this->assertEquals($expected, $resource->jsonSerialize());
    }
}