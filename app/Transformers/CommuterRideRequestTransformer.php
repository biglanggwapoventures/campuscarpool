<?php

namespace App\Transformers;

use App\RideRequest;
use League\Fractal\TransformerAbstract;
use Carbon\Carbon;

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
        $driverRoute = $rideRequest->driverRoute;
        return [
           'id' => (int)$rideRequest->id,
           'route_id' => (int)$driverRoute->id,
           'route_from' => $driverRoute->type === 'CAMPUS' ? $driverRoute->place_formatted_address : 'USC - TC',
           'route_to' => $driverRoute->type === 'CAMPUS' ? 'USC - TC' : $driverRoute->place_formatted_address,
           'departure' => $driverRoute->departure_datetime->format('m/d/Y h:i A'),
           'driver' => $driverRoute->driver->fullname(),
           'driver_id' => $driverRoute->driver->id,
           'requested_at' => $rideRequest->created_at->format('M d, Y h:i A'),
           'finished' =>  $driverRoute->departure_datetime->lt(Carbon::now())
	    ];
    }

    public function includeDriverRoute(RideRequest $rideRequest)
    {
        $driverRoute = $rideRequest->driverRoute;
        return $this->item($driverRoute, new DriverRouteDetailTransformer);
    }

}