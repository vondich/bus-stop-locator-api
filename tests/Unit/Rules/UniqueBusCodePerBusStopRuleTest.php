<?php

namespace Tests\Unit\Rules;

use App\Bus;
use App\BusStop;
use Tests\TestCase;
use App\Rules\UniqueBusCodePerBusStopRule;
use App\Contracts\Repositories\BusRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UniqueBusCodePerBusStopRuleTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var UniqueBusCodePerBusStopRule
     */
    private $rule;

    /**
     * @var BusStop
     */
    private $busStop;

    public function setUp(): void
    {
        parent::setUp();

        $this->busStop = factory(BusStop::class)->create([
            'name' => 'Serangoon Int',
            'code' => '992029',
            'lat' => 39.3416740000000000,
            'long' => 92.50175200000000,
        ]);
        
        $busRepository = $this->app->make(BusRepository::class);

        $this->rule = new UniqueBusCodePerBusStopRule($busRepository, $this->busStop->id);
    }

    public function testPassesFailed()
    {
        // create a test bus and link to our bus stop
        $bus = factory(Bus::class)->create([
            'code' => '01212'
        ]);

        $this->busStop->buses()->attach(
            $bus->id,
            [
                'first_arrival_time' => '00:00',
                'last_arrival_time' => '23:59',
            ]
        );

        $this->assertFalse($this->rule->passes('code', $bus->code));
    }

    public function testPassesSuccess()
    {
        $this->assertTrue($this->rule->passes('code', 'hey-code'));
    }
}
