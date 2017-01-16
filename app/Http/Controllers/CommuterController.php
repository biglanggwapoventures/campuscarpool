<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommuterController extends BaseController
{
    public function getRequests()
    {
        $requests  = $this->user->rideRequests;
        return $this->response->collection($requests, new \App\Transformers\CommuterRideRequestTransformer);
    }
}
