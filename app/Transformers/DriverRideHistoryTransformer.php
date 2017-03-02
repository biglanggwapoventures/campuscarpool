<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use Carbon\Carbon;
use App\DriverRoute;

class DriverRideHistoryTransformer extends TransformerAbstract
{
    public function transform(DriverRoute $request)
    {
        $commuters = [];
        if(!empty($request->rideRequests)){
            foreach($request->rideRequests AS $r){
                $commuters[] = [
                    'fullname' =>  $r->commuter->fullname,
                    'client_place' => $r->location_address
                ];
            }
        }
        $departure = Carbon::createFromFormat('Y-m-d H:i:s', $request->departure_datetime);
        return [
           'origin' => $request->type === 'HOME' ? 'USC-TC' : $request->place_formatted_address,
           'destination' => $request->type === 'HOME' ?  $request->place_formatted_address : 'USC-TC',
           'departure_datetime' => $departure->format('m/d/Y h:i A'),
           'commuters' => $commuters,
           'done' => $departure < Carbon::now()
	    ];
    }

}