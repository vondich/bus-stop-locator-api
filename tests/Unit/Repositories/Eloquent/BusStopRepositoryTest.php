<?php

namespace Tests\Unit\Repositories\Eloquent;

use App\Bus;
use App\BusStop;
use App\BusStopBus;
use Tests\TestCase;
use App\Contracts\Repositories\BusStopRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BusStopRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var BusStop
     */
    private $busStop;

    /**
     * @var BusStopRepository
     */
    private $busStopRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->busStop = factory(BusStop::class)->create([
            'name' => 'Serangoon Int',
            'code' => '992029',
            'lat' => 39.3416740000000000,
            'long' => 92.50175200000000,
        ]);
        $this->busStopRepository = $this->app->make(BusStopRepository::class);
    }

    public function testFindNearest()
    {
        $nearestBusStop = $this->busStopRepository->findNearest(39.34168, 92.501751);
        $this->assertEquals($this->busStop->id, $nearestBusStop->id);
    }

    public function testFindNearestWithRelations()
    {
        // create test buses and link to our expected bus stop
        $bus = factory(Bus::class)->create([
            'code' => '01212'
        ]);
        factory(BusStopBus::class)->create([
            'bus_stop_id' => $this->busStop->id,
            'bus_id' => $bus->id,
            'first_arrival_time' => '00:00',
            'last_arrival_time' => '23:59',
        ]);
        
        $nearestBusStop = $this->busStopRepository->findNearest(39.34168, 92.501751, ['buses']);
        $this->assertEquals($this->busStop->id, $nearestBusStop->id);
        $this->assertTrue($nearestBusStop->relationLoaded('buses'));

        $this->assertCount(1, $nearestBusStop->buses);
        $actualBus = $nearestBusStop->buses->first();
        $this->assertEquals($bus->id, $actualBus->id);
    }
}
