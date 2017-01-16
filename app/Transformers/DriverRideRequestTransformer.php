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
    protected $availableIncludes = [
        'commuter'
    ];

    public function transform(RideRequest $rideRequest)
    {
        return [
           'id' => (int)$rideRequest->id,
           'commuter_id' => (int)$rideRequest->commuter_id,
           'num_seats' => (int)$rideRequest->num_seats,
           'accepted' => $rideRequest->accepted,
           'rejected' => $rideRequest->rejected,
           'requested_at' => $rideRequest->created_at->format('M d, Y h:i A')
	    ];
    }

    public function includeCommuter(RideRequest $rideRequest)
    {
        $commuter = $rideRequest->commuter;
        return $this->item($commuter, new CommuterTransformer);
    }

}