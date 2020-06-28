<?php

namespace Tests\Unit\Services;

use App\Bus;
use App\BusStop;
use Tests\TestCase;
use App\Services\RegisterBusForBusStop;
use App\Contracts\Repositories\BusRepository;
use App\Contracts\Repositories\BusStopRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegisterBusForBusStopTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var BusStop
     */
    private $busStop;

    /**
     * @var RegisterBusForBusStop
     */
    private $registerBusForBusStopService;

    public function setUp(): void
    {
        parent::setUp();

        $this->busStop = factory(BusStop::class)->create([
            'name' => 'Serangoon Int',
            'code' => '992029',
            'lat' => 39.3416740000000000,
            'long' => 92.50175200000000,
        ]);
        
        $busStopRepository = $this->app->make(BusStopRepository::class);
        $busRepository = $this->app->make(BusRepository::class);

        $this->registerBusForBusStopService = $this->app->make(
            RegisterBusForBusStop::class,
            [
                $busStopRepository,
                $busRepository
            ]
        );
    }

    public function testStoreNewBus()
    {
        $busStopBus = $this->registerBusForBusStopService->store([
            'bus_stop_id' => $this->busStop->id,
            'code' => '121212',
            'first_arrival_time' => '10:00',
            'last_arrival_time' => '22:00',
        ]);

        // check whether bus is created
        $expectedBus = Bus::where('code', '121212')->first();
        $this->assertNotNull($expectedBus);

        // check whether bus is link to the bus stop
        $this->assertDatabaseHas('bus_stop_buses', [
            'bus_stop_id' => $this->busStop->id,
            'bus_id' => $expectedBus->id,
            'first_arrival_time' => '10:00',
            'last_arrival_time' => '22:00',
        ]);
    }

    public function testStoreExistingBus()
    {
        // create a test bus
        factory(Bus::class)->create([
            'code' => '01212'
        ]);

        $busStopBus = $this->registerBusForBusStopService->store([
            'bus_stop_id' => $this->busStop->id,
            'code' => '01212',
            'first_arrival_time' => '10:00',
            'last_arrival_time' => '22:00',
        ]);
        $this->assertNotNull($busStopBus);

        // check whether bus is not created again
        $this->assertEquals(1, Bus::where('code', '01212')->count());
        
        // check whether bus is link to the bus stop
        $expectedBus = Bus::where('code', '01212')->first();
        $this->assertNotNull($expectedBus);
        $this->assertDatabaseHas('bus_stop_buses', [
            'bus_stop_id' => $this->busStop->id,
            'bus_id' => $expectedBus->id,
            'first_arrival_time' => '10:00',
            'last_arrival_time' => '22:00',
        ]);
    }
}
