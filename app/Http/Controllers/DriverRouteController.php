<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\RouteRequest;
use App\Http\Requests\RideRequestRequest;
// use Dingo\Api\Routing\Helpers;
use App\DriverRoute;
use App\RideRequest;
use App\Message;
use Validator;
use Carbon\Carbon;

class DriverRouteController extends Controller
{
    
    // use Helpers;

    public function store(RouteRequest $request)
    {
        $data = $request->only([
            'type',
            'max_passenger',
            'fare_contribution',
            'additional_details',
            'route_index'
        ]);
        $data += [
            'place_id' => $request->input('place.id'),
            'place_formatted_address'  => $request->input('place.formatted_address'),
            'place_latitude'  => $request->input('place.latitude'),
            'place_longitude'  => $request->input('place.longitude'),
            'created_by' => $this->auth->user()->id,
            'departure_datetime' => Carbon::createFromFormat('Y-m-d H:i', $request->input('departure_datetime'))->format('Y-m-d H:i:s')
        ];

        $route = DriverRoute::create($data);

        if(!$route->id){
            return $this->response->errorInternal('Failed to create new route.');
        }

        return $this->response->created();

    }

    public function all()
    {
        $routes = DriverRoute::active()->get();
        return $this->response->collection(
            $routes, 
            new \App\Transformers\DriverRouteListTransformer, 
            [], 
            function($resource, $fractal){
                $fractal->parseIncludes('driver');
            }
        );
    }

    public function fetch($id)
    {
        $driverRoute = DriverRoute::find($id);
        // dd($driverRoute);
        return $this->response->item(
            $driverRoute, 
            new \App\Transformers\DriverRouteDetailTransformer, 
            [], 
            function($resource, $fractal){
                $fractal->parseIncludes('driver');
            }
        );
    }

    public function request(RideRequestRequest $request)
    {
        //check if user has active ride request
        $lastRequest = $this->auth->user()->rideRequests()->orderBy('created_at', 'DESC')->first();
        //  return $this->response->array($lastRequest->toArray());
        if($lastRequest && $lastRequest->isActive()){
            // return $this->response->array($lastRequest->toArray());
            // dd($lastRequest->toArray());
            // user has active ride request, send error
            return $this->response->errorBadRequest('Cannot perform action: You have an active ride request.');
        }

        // fetch driver route details
        $routeId = $request->driver_route_id;
        $driverRoute = DriverRoute::find($routeId);


        // check if ride has avaialble seats
        if($driverRoute->seatsFull()){
            return $this->response->errorBadRequest(
                'Failed to send a driver a request. No more available seats.'
            );
        }


        $pickup = explode(',', $request->input('pickup'));
        $dropoff = explode(',', $request->input('dropoff'));

        // create ride rquests
        $rideRequest = new RideRequest([
            'driver_route_id' => $routeId,
            'commuter_id' => $this->auth->user()->id,
            'pickup_latitude' => $pickup[0],
            'pickup_longitude' => $pickup[1],
            'dropoff_latitude' => $dropoff[0],
            'dropoff_longitude' => $dropoff[1],
        ]);
        

        if($request->has('num_seats'))  
            $rideRequest->num_seats = $request->input('num_seats');

        $this->auth->user()->sendMessage($request->input('message'), $driverRoute->created_by);

        $rideRequest = $driverRoute->rideRequests()->save($rideRequest);

        if(!$rideRequest->id){
            return $this->response->errorInternal(
                'Failed to send a driver a request. Please try again later.'
            );
        }

        return $this->response->created();

    }
    
    public function myRoutes()
    {
        $postedRoutes = $this->auth->user()
            ->postedRoutes()
            ->orderBy('created_at', 'DESC')
            ->get();

        return $this->response->collection($postedRoutes, new \App\Transformers\DriverRouteListTransformer);
    }

    public function getRequests($id)
    {
        // sleep(3);
        $rideRequests = DriverRoute::find($id)->rideRequests()->get();
        return $this->response->collection(
            $rideRequests, 
            new \App\Transformers\DriverRideRequestTransformer, 
            [], 
            function($resource, $fractal){
                $fractal->parseIncludes('commuter');
            }
        );
    }

    public function setRequest($requestId, Request $payload)
    {
        try{
            $rideRequest = RideRequest::findOrFail($requestId);
        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){
            throw new \Dingo\Api\Exception\UpdateResourceFailedException('Could not set status for ride request.', ['Ride request does not exist.']);
        }

        $v = Validator::make($payload->all(), [
            'status' => 'required|in:a,r'
        ]);

        if ($v->fails()) throw new \Dingo\Api\Exception\UpdateResourceFailedException('Could not set status for ride request.', array_values($validator->errors()));

        if($payload->input('status') === 'a') $rideRequest->accepted = true;
        else $rideRequest->rejected = true;
        $rideRequest->save();

        return $this->response->noContent();
        
    }
}
