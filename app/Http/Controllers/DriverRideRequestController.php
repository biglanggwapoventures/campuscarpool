<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RideRequest;

class DriverRideRequestController extends Controller
{
    public function accept($requestId)
    {
        try{
            $request = RideRequest::find($requestId);
            if($request->accepted || $request->rejected)
                return $this->response->errorBadRequest('Request has already been accepted or rejected.');

            if($request->driverRoute->seatsFull())
                return $this->response->errorBadRequest('Max limit for passengers on this route has been reached.');

            $request->accepted = true;
            $request->save();

            return $this->response->noContent();
        }catch(Illuminate\Database\Eloquent\ModelNotFoundException $e){
            return $this->response->errorNotFound();
        }
    }

    public function reject($requestId)
    {
        try{
            $request = RideRequest::find($requestId);
            if($request->accepted || $request->rejected)
                return $this->response->errorBadRequest('Request has already been accepted or rejected.');

            $request->rejected = true;
            $request->save();
            
            return $this->response->noContent();
        }catch(Illuminate\Database\Eloquent\ModelNotFoundException $e){
            return $this->response->errorNotFound();
        }
    }

    public function send($requestId)
    {
        
    }

    public function cancel($requestId)
    {
        
    }
    

    public function fetch($requestId)
    {
        try{
            $request = RideRequest::find($requestId);
            return $this->response->array([
                'status' => $request->getStatus()
            ]);
        }catch(Illuminate\Database\Eloquent\ModelNotFoundException $e){
            return $this->response->errorNotFound();
        }
    }

    public function rate($id, Request $request)
    {
        try{
            $rideRequest = RideRequest::find($id);
            $rideRequest->commuter_rating = $request->input('rating');
            return $this->response->noContent();
        }catch(Illuminate\Database\Eloquent\ModelNotFoundException $e){
            return $this->response->errorNotFound();
        }
    }
}


