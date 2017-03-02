<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\RouteRequest;
use App\Http\Requests\RideRequestRequest;
use App\DriverRoute;
use App\RideRequest;
use App\Message;

use Validator;
use Illuminate\Validation\Rule;

use Carbon\Carbon;
use DB;
use App\User;

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

    public function all(Request $request)
    {
        // DB::enableQueryLog();

        $routes = DriverRoute::active();
        if(in_array($request->input('type'), ['CAMPUS','HOME'])){
            $routes->where('type', trim($request->input('type')));
        }

        $v = Validator::make($request->all(), [
            'datetime' => 'present|date_format:"Y-m-d H:i"'
        ]);

        if(!$v->fails()){
            // dd(($request->input('type')));
            if(trim($request->datetime)){
                 $routes->where('departure_datetime', '>=', $request->input('datetime'))
                    ->whereRaw("DATE(departure_datetime) = DATE('{$request->input('datetime')}')");;
            }
        }
        
        $result = $routes->orderBy('departure_datetime', 'ASC')->get();

        // dd(DB::getQueryLog());
        return $this->response->collection(
            $result, 
            new \App\Transformers\DriverRouteListTransformer, 
            [], 
            function($resource, $fractal){
                $fractal->parseIncludes('driver');
            }
        );
    }

    public function fetch($id, Request $request)
    {
        $includes = 'driver';
        if($request->input('includes') === 'accepted-requests'){
            $includes.=',acceptedRequests';
        }
        $driverRoute = DriverRoute::find($id);
        // dd($driverRoute);
        return $this->response->item(
            $driverRoute, 
            new \App\Transformers\DriverRouteDetailTransformer, 
            [], 
            function($resource, $fractal) USE ($includes){
                $fractal->parseIncludes($includes);
            }
        );
    }

    public function request(RideRequestRequest $request)
    {
        //check if user has active ride request
        $activeRequests = $this->auth->user()->rideRequests()->currentDay()->active()->get();
        if($activeRequests->count() >= 3){
            return $this->response->errorBadRequest('Cannot perform action: You can only have 3 requests per day.');
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

        $location = explode(',', $request->input('coordinates'));

        // create ride rquests
        $rideRequest = new RideRequest([
            'driver_route_id' => $routeId,
            'commuter_id' => $this->auth->user()->id,
            'location_latitude' => $location[0],
            'location_longitude' => $location[1],
            'location_address' => $request->input('formatted_address')
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
    
    public function myRoutes(Request $request)
    {
        $postedRoutes = $this->auth->user()->postedRoutes();

        if($request->type === 'ACTIVE'){
            $postedRoutes->whereRaw('departure_datetime >= NOW()');
        }else{
            $postedRoutes->whereRaw('departure_datetime <= NOW()');
        }
        
        $results = $postedRoutes->orderBy('created_at', 'DESC')->get();

        return $this->response->collection($results, new \App\Transformers\DriverRouteListTransformer);
    }

    public function getRequests($id, Request $request)
    {
        $rideRequests = RideRequest::where('driver_route_id', $id);
        switch($request->input('type')){
            case 'accepted': 
                $rideRequests
                    ->where('accepted', '=', 1)
                    ->whereNull('cancelled_at');
                break;
            case 'rejected': 
                 $rideRequests->where('rejected', '=', 1)
                    ->whereNull('cancelled_at');
                break;
            case 'cancelled': 
                 $rideRequests->whereNotNull('cancelled_at');
                break;
            default: 
                 $rideRequests
                    ->where('accepted', 0)
                    ->where('rejected', 0)
                    ->whereNull('cancelled_at');
                break;
        }

        $result = $rideRequests->get();
        return $this->response->collection(
            $result, 
            new \App\Transformers\DriverRideRequestTransformer
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


    public function getRequest($id)
    {

        $request = RideRequest::find($id);

        $route = DriverRoute::find($request->driver_route_id);
        $route->done = $route->isDone();

        $commuter = User::select('firstname', 'lastname', 'display_photo', 'role', 'id')->where('id', $request->commuter_id)->first();
        // dd();
        $commuter->rating = $commuter->averageRating();

        return $this->response->array([
            'data' => compact('request', 'route', 'commuter')
        ]);
    }

    public function fetchAll($routeId)
    {
         $route = DriverRoute::find($routeId);
         $route->done = $route->isDone();

         $driver =  User::select('firstname', 'lastname', 'display_photo')->where('id', $route->created_by)->first();

         $commuters = RideRequest::select('id', 'commuter_id', 'location_longitude', 'location_latitude', 'location_address', 'commuter_rating')
            ->with(['commuter' => function($q){
                $q->select('id', 'firstname', 'lastname', 'display_photo', 'role');
            }])
            ->where([
                ['driver_route_id', '=', $route->id],
                ['accepted', '=', 1]
            ])
            ->whereNull('cancelled_at')
            ->get();

        $commuters->each(function (&$item, $key) USE ($routeId){
            $item->commuter->rating = $item->commuter->averageRating();
            $item->commuter->reported = $item->commuter->isReported($routeId);
        });

        return $this->response->array([
            'data' => compact('route', 'driver', 'commuters')
        ]);
         
    }

    public function saveRating($routeId, Request $request)
    {

        try{

            $driverRoute = DriverRoute::findOrFail($routeId);

            if(!(int)$driverRoute->isDone() || $driverRoute->ratings_done)
                return $this->response->errorBadRequest('Cannot rate. Route is not yet finished or you already gave the commuters their respective ratigns!');

        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){
            throw new \Dingo\Api\Exception\UpdateResourceFailedException('Could not set ratings ', ['Route does not exist.']);
        }

        $v = Validator::make($request->all(), [
            'ratings.*.commuter_id' => [
                'required_with:ratings.*.rating',  
                Rule::exists('ride_requests')->where(function ($query)  USE($routeId) {
                    $query->where('driver_route_id', $routeId)
                        ->where('accepted', 1)
                        ->whereNull('cancelled_at');
                }),
            ],
            'ratings.*.rating' => 'required_with:ratings.*.commuter_id|in:1,2,3,4,5'
        ]);

        if ($v->fails()) throw new \Dingo\Api\Exception\UpdateResourceFailedException('Could not set save commuter ratings', $v->errors()->all());

        DB::transaction(function () USE ($request, $routeId) {

            foreach($request->ratings AS $r){
                RideRequest::where('commuter_id', $r['commuter_id'])->update(['commuter_rating' => $r['rating']]);
            }
            DriverRoute::where('id', $routeId)->update(['ratings_done' => 1]);

        }, 3);

        return $this->response->noContent();

    }
}
