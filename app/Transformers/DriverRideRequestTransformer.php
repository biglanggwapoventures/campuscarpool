<?php

namespace App\Transformers;

use App\RideRequest;
use League\Fractal\TransformerAbstract;

class DriverRideRequestTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'commuter'
    ];

    public function transform(RideRequest $rideRequest)
    {
        if($rideRequest->accepted)
            $status = 'Accepted';
        else if($rideRequest->rejected)
            $status = 'Rejected';
        else
            $status = 'Pending';
        
        return [
           'id' => (int)$rideRequest->id,
           'commuter_id' => (int)$rideRequest->commuter_id,
           'num_seats' => (int)$rideRequest->num_seats,
           'accepted' => $rideRequest->accepted,
           'latitude' => $rideRequest->location_latitude,
           'longitude' => $rideRequest->location_longitude,
           'location' => $rideRequest->location_address,
           'rejected' => $rideRequest->rejected,
           'status' => $status,
           'requested_at' => $rideRequest->created_at->format('M d, Y h:i A')
	    ];
    }

    public function includeCommuter(RideRequest $rideRequest)
    {
        $commuter = $rideRequest->commuter;
        return $this->item($commuter, new CommuterTransformer);
    }

}