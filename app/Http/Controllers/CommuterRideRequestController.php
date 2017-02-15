<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RideRequest;
use App\DriverRoute;
use App\User;
use Carbon\Carbon;

class CommuterRideRequestController extends Controller
{

    public function all(Request $request)
    {
        $commuterId = $this->auth->user()->id;
        $rideRequests = RideRequest::where('commuter_id', $commuterId);
        switch($request->input('type')){
            case 'accepted': 
                $rideRequests
                    ->where('accepted', '=', 1)
                    ->whereNull('cancelled_at');
                break;
            case 'rejected': 
                 $rideRequests
                    ->where('rejected', '=', 1);
                break;
            case 'cancelled': 
                 $rideRequests
                    ->whereNotNull('cancelled_at');
                break;
            default: 
                 $rideRequests
                    ->whereNull('cancelled_at')
                    ->where('accepted', 0)
                    ->where('rejected', 0);
                break;
        }

        $result = $rideRequests
            ->orderBy('created_at', 'DESC')
            ->get();
        return $this->response->collection(
            $result, 
            new \App\Transformers\CommuterRideRequestTransformer
        );
    }

    public function active()
    {
        $result = false;
        $status = null;
        $lastRequest = $this->auth->user()->rideRequests()->orderBy('created_at', 'DESC')->first();
        if($lastRequest && $lastRequest->isActive()){
            $result = true;
            $status = $lastRequest->getStatus();
        }
        return $this->response->array([
            'result' => $result,
            'status' =>  $status
        ]);
    }
    
    public function fetch($id)
    {
        try{
            
            $request = RideRequest::find($id);

            $route = DriverRoute::find($request->driver_route_id);
            $route->done = $route->isDone();
            
            $driver = User::select('firstname', 'lastname', 'display_photo')->where('id', $route->created_by)->first();

            return $this->response->array([
                'data' => compact('request', 'route', 'driver')
            ]);
        }catch(Illuminate\Database\Eloquent\ModelNotFoundException $e){
            return $this->response->errorNotFound();
        }
    }

    public function cancel($id)
    {
        try{
            
            $request = RideRequest::find($id);
            $request->cancelled_at = Carbon::now();
            $request->save();

            return $this->response->noContent();
           
        }catch(Illuminate\Database\Eloquent\ModelNotFoundException $e){
            return $this->response->errorNotFound();
        }
    }

     public function rate($id, Request $request)
    {
          try{
            
            $rideRequest = RideRequest::find($id);

            if(!$rideRequest->driverRoute->isDone())
                return $this->response->errorBadRequest('Cannot rate. Carpool is not yet done');

            if((int)$rideRequest->driver_rating > 0)
                return $this->response->errorBadRequest('Cannot rate. You already have given the driver a rating');

            // dd( $request->input('rating'));
            $rideRequest->driver_rating = (int)$request->input('rating');
            $rideRequest->save();
            

            return $this->response->noContent();

        }catch(Illuminate\Database\Eloquent\ModelNotFoundException $e){
            return $this->response->errorNotFound();
        }
    }

    

    
}
