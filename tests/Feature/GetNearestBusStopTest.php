<?php

namespace Tests\Feature;

use App\Bus;
use App\User;
use App\BusStop;
use App\BusStopBus;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GetNearestBusStopTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test validations for required fields
     *
     * @return void
     */
    public function testGetNearestBusStopErrorMissingRequiredFields()
    {
        $user = User::find(1);

        $this->actingAs($user, 'api')
            ->json('GET', '/api/bus-stops/nearest')
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'source_lat' => ['The source lat field is required.'],
                    'source_long' => ['The source long field is required.'],
                ]
            ]);
    }

    /**
     * Test validations for source coordinates
     *
     * @return void
     */
    public function testGetNearestBusStopErrorInvalidSourceCoordinates()
    {
        $user = User::find(1);

        $this->actingAs($user, 'api')
            ->json('GET', '/api/bus-stops/nearest?' . http_build_query([
                'source_lat' => 'test',
                'source_long' => 'test',
            ]))
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'source_lat' => ['The source lat must be a number.'],
                    'source_long' => ['The source long must be a number.'],
                ]
            ]);
    }

    /**
     * Test successful request with no registered buses for bus stop
     *
     * @return void
     */
    public function testGetNearestBusStopSuccessNoRegisteredBuses()
    {
        $user = User::find(1);
        $expectedBusStop = BusStop::find(1);

        $this->actingAs($user, 'api')
            ->json('GET', '/api/bus-stops/nearest?' . http_build_query([
                'source_lat' => $expectedBusStop->lat,
                'source_long' => $expectedBusStop->long,
            ]))
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $expectedBusStop->id,
                    'name' => $expectedBusStop->name,
                    'code' => $expectedBusStop->code,
                    'lat' => $expectedBusStop->lat,
                    'long' => $expectedBusStop->long,
                    'buses' => []
                ]
            ]);
    }

    /**
     * Test successful request with registered buses for bus stop
     *
     * @return void
     */
    public function testGetNearestBusStopSuccessWithRegisteredBuses()
    {
        $user = User::find(1);
        $expectedBusStop = factory(BusStop::class)->create([
            'name' => 'Serangoon Int',
            'code' => '992029',
            'lat' => 39.3416740000000000,
            'long' => 92.50175200000000,
        ]);

        // create test buses and link to our expected bus stop
        $bus = factory(Bus::class)->create([
            'code' => '01212'
        ]);
        $busStopBus = factory(BusStopBus::class)->create([
            'bus_stop_id' => $expectedBusStop->id,
            'bus_id' => $bus->id,
            'first_arrival_time' => '00:00',
            'last_arrival_time' => '23:59',
        ]);

        $this->actingAs($user, 'api')
            ->json('GET', '/api/bus-stops/nearest?' . http_build_query([
                'source_lat' => $expectedBusStop->lat,
                'source_long' => $expectedBusStop->long,
            ]))
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $expectedBusStop->id,
                    'name' => $expectedBusStop->name,
                    'code' => $expectedBusStop->code,
                    'lat' => $expectedBusStop->lat,
                    'long' => $expectedBusStop->long,
                    'buses' => [
                        [
                            'code' => $bus->code,
                            'first_arrival_time' => $busStopBus->first_arrival_time,
                            'last_arrival_time' => $busStopBus->last_arrival_time,
                        ]
                    ]
                ]
            ]);
    }
}
