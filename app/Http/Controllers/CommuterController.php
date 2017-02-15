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

    public function hasActiveRideRequest()
    {
        $lastRequest = $this->auth->user()->rideRequests()->orderBy('created_at', 'DESC')->first();
        return $this->response->array([
            'result' => $lastRequest && $lastRequest->isActive()
        ]);
    }
}
