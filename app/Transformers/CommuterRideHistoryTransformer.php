<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use Carbon\Carbon;
use App\RideRequest;

class CommuterRideHistoryTransformer extends TransformerAbstract
{
    public function transform(RideRequest $request)
    {
        $departure = Carbon::createFromFormat('Y-m-d H:i:s', $request->driverRoute->departure_datetime);
        return [
           'driver' => $request->driverRoute->driver->fullname,
           'driver_id' => $request->driverRoute->driver->id,
           'origin' => $request->driverRoute->type === 'HOME' ? 'USC-TC' : $request->driverRoute->place_formatted_address,
           'destination' => $request->driverRoute->type === 'HOME' ? $request->driverRoute->place_formatted_address : 'USC-TC',
           'client_place' => $request->location_address,
           'departure_datetime' => $departure->format('m/d/Y h:i A'),
           'done' => $departure < Carbon::now()
	    ];
    }

}