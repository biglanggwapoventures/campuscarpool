<?php

namespace App\Transformers;

use App\DriverRoute;
use League\Fractal\TransformerAbstract;
use Carbon\Carbon;

class DriverRouteDetailTransformer extends TransformerAbstract
{
     /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'driver',
        'acceptedRequests'
    ];

	 /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(DriverRoute $driverRoute)
	{
        $routeFrom = $driverRoute->type === 'CAMPUS' ? $driverRoute->place_formatted_address : 'USC - TC';
        $routeTo = $driverRoute->type === 'CAMPUS' ? 'USC - TC' : $driverRoute->place_formatted_address;
	    return [
           'id' => (int)$driverRoute->id,
           'type' => $driverRoute->type,
           'route_from' => $routeFrom,
           'route_to' => $routeTo,
           'place' => [
               'id' => $driverRoute->place_id,
               'latitude' => $driverRoute->place_latitude,
               'longitude' => $driverRoute->place_longitude,
               'route_index' => $driverRoute->route_index,
           ],
           'additional_details' => $driverRoute->additional_details,
           'num_seats_max' => (int) $driverRoute->max_passenger,
           'num_seats_taken' =>  $driverRoute->numSeatsTaken(),
           'departure_datetime' => $driverRoute->departure_datetime->format('M d, Y h:i A'),
           'posted_at' => $driverRoute->created_at->format('M d, Y h:i A'),
	    ];
	}

    /**
     * Include Driver
     *
     * @return League\Fractal\ItemResource
     */
    public function includeDriver(DriverRoute $driverRoute)
    {
        $driver = $driverRoute->driver;
        return $this->item($driver, new DriverTransformer);
    }

    public function includeAcceptedRequests(DriverRoute $driverRoute)
    {
        $requests = $driverRoute->acceptedRequests;
        return $this->collection($requests, new DriverRideRequestTransformer);
    }
}