<?php

namespace Tests\Feature;

use App\Bus;
use App\User;
use App\BusStop;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegisterBusForBusStopTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test validations for required fields
     *
     * @return void
     */
    public function testRegisterBusForBusStopErrorMissingRequiredFields()
    {
        $user = User::find(1);

        $this->actingAs($user, 'api')
            ->json('POST', '/api/bus-stops/1/buses')
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'code' => ['The code field is required.'],
                    'first_arrival_time' => ['The first arrival time field is required.'],
                    'last_arrival_time' => ['The last arrival time field is required.'],
                ]
            ]);
    }

    /**
     * Test validations for request inputs
     *
     * @return void
     */
    public function testRegisterBusForBusStopErrorInvalidInput()
    {
        $user = User::find(1);

        $this->actingAs($user, 'api')
            ->json(
                'POST', 
                '/api/bus-stops/99999/buses', 
                [
                    'code' => 'somewhatlongcode',
                    'first_arrival_time' => 'test',
                    'last_arrival_time' => 'test',
                ]
            )
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'bus_stop_id' => ['The selected bus stop id is invalid.'],
                    'code' => ['The code may not be greater than 10 characters.'],
                    'first_arrival_time' => ['The first arrival time does not match the format H:i.'],
                    'last_arrival_time' => ['The last arrival time does not match the format H:i.'],
                ]
            ]);
    }

    /**
     * Test that a bus can only be registered once for a bus stop
     *
     * @return void
     */
    public function testRegisterBusForBusStopErrorDuplicatedBus()
    {
        $user = User::find(1);

        $user = User::find(1);
        $busStop = factory(BusStop::class)->create([
            'name' => 'Serangoon Int',
            'code' => '992029',
            'lat' => 39.3416740000000000,
            'long' => 92.50175200000000,
        ]);

        // create a test bus and link to our bus stop
        $bus = factory(Bus::class)->create([
            'code' => '01212'
        ]);

        $busStop->buses()->attach(
            $bus->id,
            [
                'first_arrival_time' => '00:00',
                'last_arrival_time' => '23:59',
            ]
        );

        // register the created bus to the same bus stop
        $this->actingAs($user, 'api')
            ->json(
                'POST', 
                "/api/bus-stops/{$busStop->id}/buses", 
                [
                    'code' => $bus->code,
                    'first_arrival_time' => '00:00',
                    'last_arrival_time' => '23:59',
                ]
            )
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'code' => ['The bus code must be unique per bus stop.'],
                ]
            ]);
    }

    /**
     * Test successful request when bus code does not exist yet, a new bus must be created.
     *
     * @return void
     */
    public function testRegisterBusForBusStopSuccessNewBus()
    {
        $user = User::find(1);
        $busStop = BusStop::find(1);

        $this->actingAs($user, 'api')
            ->json(
                'POST', 
                "/api/bus-stops/{$busStop->id}/buses", 
                [
                    'code' => '718117',
                    'first_arrival_time' => '00:00',
                    'last_arrival_time' => '23:59',
                ]
            )
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'bus' => [
                        'code' => '718117'
                    ],
                    'first_arrival_time' => '00:00',
                    'last_arrival_time' => '23:59',
                ]
            ]);

        // check whether bus is created
        $expectedBus = Bus::where('code', '718117')->first();
        $this->assertNotNull($expectedBus);

        // check whether bus is link to the bus stop
        $this->assertDatabaseHas('bus_stop_buses', [
            'bus_stop_id' => $busStop->id,
            'bus_id' => $expectedBus->id,
            'first_arrival_time' => '00:00',
            'last_arrival_time' => '23:59',
        ]);
    }

    /**
     * Test successful request when bus code already exists, a new bus must not be created but must be linked to the bus stop
     *
     * @return void
     */
    public function testRegisterBusForBusStopSuccessExistingBus()
    {
        $user = User::find(1);
        $busStop = BusStop::find(1);

        // create a test bus
        $bus = factory(Bus::class)->create([
            'code' => '01212'
        ]);

        $this->actingAs($user, 'api')
            ->json(
                'POST', 
                "/api/bus-stops/{$busStop->id}/buses", 
                [
                    'code' => '01212',
                    'first_arrival_time' => '00:00',
                    'last_arrival_time' => '23:59',
                ]
            )
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'bus' => [
                        'code' => '01212'
                    ],
                    'first_arrival_time' => '00:00',
                    'last_arrival_time' => '23:59',
                ]
            ]);

        $expectedBus = Bus::where('code', '01212')->first();
        $this->assertNotNull($expectedBus);

        // check whether bus is link to the bus stop
        $this->assertDatabaseHas('bus_stop_buses', [
            'bus_stop_id' => $busStop->id,
            'bus_id' => $expectedBus->id,
            'first_arrival_time' => '00:00',
            'last_arrival_time' => '23:59',
        ]);
    }
}
