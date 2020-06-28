<?php

namespace App\Http\Requests\BusStop;

use Illuminate\Foundation\Http\FormRequest;

class GetNearestBusStopRequest extends FormRequest
{
    public function rules()
    {
        return [
            'source_lat' => 'required|numeric',
            'source_long' => 'required|numeric'
        ];
    }

    public function authorize()
    {
        return true;
    }
}