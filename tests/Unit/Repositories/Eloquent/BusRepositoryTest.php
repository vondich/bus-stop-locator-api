<?php

namespace Tests\Unit\Repositories\Eloquent;

use App\Bus;
use App\BusStop;
use Tests\TestCase;
use App\Contracts\Repositories\BusRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BusRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var Bus
     */
    private $bus;

    /**
     * @var BusRepository
     */
    private $busRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->bus = factory(Bus::class)->create();
        $this->busRepository = $this->app->make(BusRepository::class);
    }

    public function testExistsForBusStopId()
    {
        $busStop = factory(BusStop::class)->create([
            'name' => 'Serangoon Int',
            'code' => '992029',
            'lat' => 39.3416740000000000,
            'long' => 92.50175200000000,
        ]);

        // test unlinked bus to bus stop
        $this->assertFalse(
            $this->busRepository->existsForBusStopId($busStop->id, $this->bus->code)
        );

        // test linked bus to bus stop
        $busStop->buses()->attach(
            $this->bus->id,
            [
                'first_arrival_time' => '00:00',
                'last_arrival_time' => '23:59',
            ]
        );
        $this->assertTrue(
            $this->busRepository->existsForBusStopId($busStop->id, $this->bus->code)
        );
    }

    public function testFindByBusCode()
    {
        // test non existing bus code
        $bus = $this->busRepository->findByBusCode('some-unknown-bus-code');
        $this->assertNull($bus);

        // test an existing bus code
        $bus = $this->busRepository->findByBusCode($this->bus->code);
        $this->assertNotNull($bus);
        $this->assertEquals($this->bus->id, $bus->id);
    }
}
