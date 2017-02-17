<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Report;


class ReportController extends Controller
{
    public function store(Request $request)
    {
        $user = $this->auth->user();

        $v = Validator::make($request->all(), [
            'driver_route_id' => 'required',
            'recipient_id' => 'required',
            'message' => 'required',
        ]);

        if($v->fails()){
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Validation errors..', $v->errors()->all());
        }


        $report = $this->user->reports()->create(
            $request->only(['driver_route_id', 'recipient_id', 'message'])
        );

        if($report->id){
            return $this->response->noContent();
        }

        return $this->response->errorInternal();

    }
}
