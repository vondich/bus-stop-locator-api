<?php

namespace App\Http\Requests\Bus;

use App\Contracts\Repositories\BusRepository;
use App\Rules\UniqueBusCodePerBusStopRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterBusRequest extends FormRequest
{
    public function rules(BusRepository $busRepository)
    {
        return [
            'bus_stop_id' => [
                'required',
                'int',
                'exists:bus_stops,id'
            ],
            'code' => [
                'required',
                'alpha_num',
                'max:10',
                new UniqueBusCodePerBusStopRule($busRepository, $this->route('bus_stop_id'))
            ],
            'first_arrival_time' => [
                'required',
                'date_format:H:i'
            ],
            'last_arrival_time' => [
                'required',
                'date_format:H:i'
            ],
        ];
    }

    public function all($keys = null)
    {
        $all = parent::all();
        $all['bus_stop_id'] = $this->route('bus_stop_id');

        return $all;
    }

    public function authorize()
    {
        return true;
    }
}