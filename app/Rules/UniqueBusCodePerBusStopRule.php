<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Contracts\Repositories\BusRepository;

/**
 * Validates that every bus linked to a bus stop is unique
 */
class UniqueBusCodePerBusStopRule implements Rule
{
    private $busStopId;
    private $busRepository;

    public function __construct(BusRepository $busRepository, $busStopId)
    {
        $this->busStopId = $busStopId;
        $this->busRepository = $busRepository;
    }

     /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return ($this->busRepository->existsForBusStopId($this->busStopId, $value)) === false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.custom.code.unique_per_bus_stop');
    }
}