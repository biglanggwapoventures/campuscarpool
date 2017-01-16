<?php

namespace App\Transformers;

use App\RideRequest;
use League\Fractal\TransformerAbstract;

class CommuterRideRequestTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'driverRoute'
    ];

    public function transform(RideRequest $rideRequest)
    {
        $driverRoute = $rideRequest->driverRoute()->with('driver')->first();
        return [
           'id' => (int)$rideRequest->id,
           'route_id' => (int)$driverRoute->id,
           'destination' => $driverRoute->type === 'CAMPUS' ? 'USC - TC' : $driverRoute->place_formatted_address,
           'departure' => $driverRoute->departure_datetime->format('M d, Y h:i A'),
           'driver' => "{$driverRoute->driver->firstname} {$driverRoute->driver->lastname}",
           'driver_id' => $driverRoute->driver->id,
           'status' => $rideRequest->accepted ? 'A' : ($rideRequest->rejected ? 'R' : 'P'),
           'requested_at' => $rideRequest->created_at->format('M d, Y h:i A'),
	    ];
    }

    public function includeDriverRoute(RideRequest $rideRequest)
    {
        $driverRoute = $rideRequest->driverRoute;
        return $this->item($driverRoute, new DriverRouteDetailTransformer);
    }

}