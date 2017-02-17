<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;

use App\User;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function users(Request $request)
    {
        $results = User::select()->where('role', '!=', 'ADMIN');

        if(trim($request->name)){
            $results->where(function($q) USE ($request) {
                $q->whereRaw("CONCAT(firstname, ' ', lastname) LIKE '%{$request->name}%'");
            });
        }

        if(in_array($request->type, ['driver', 'commuter'])){
            $results->where('role', strtoupper($request->type));
        }

        if(in_array($request->status, ['approved', 'rejected', 'pending'])){
            switch($request->status){
                case 'approved':
                    $results->whereNotNull('approved_at')->whereNull('rejected_at');
                    break;
                case 'rejected':
                    $results->whereNotNull('rejected_at')->whereNull('approved_at');
                    break;
                case 'pending':
                    $results->whereNull('rejected_at')->whereNull('approved_at');
                    break;
            }
        }

        return $this->response->collection($results->get(), new \App\Transformers\UserListTransformer);
    }

    public function reports(Request $request)
    {
        $results = \App\Report::select('id', 'message', 'created_at', 'recipient_id', 'sender_id')->with('sender', 'recipient');

        if(trim($request->name)){
            $results->whereHas('recipient', function ($q) USE ($request) {
                if(trim($request->name)){
                    $q->whereRaw("CONCAT(firstname, ' ', lastname) LIKE '%{$request->name}%'");
                }
            });
        }

        $startDateValidator = Validator::make($request->all(), [
            'start_date' => 'date_format:"Y-m-d"'
        ]);

        $endDateValidator = Validator::make($request->all(), [
            'end_date' => 'date_format:"Y-m-d"'
        ]);

        if(!$startDateValidator->fails() && trim($request->start_date)) $results->whereRaw("DATE(created_at) >= '{$request->start_date}'");
        if(!$endDateValidator->fails() && trim($request->end_date)) $results->whereRaw("DATE(created_at) <= '{$request->end_date}'");

        // dd($results->get()->toArray());

        return $this->response->collection($results->get(), new \App\Transformers\ReportListTransformer);
    }

    public function fetchUser($userId)
    {
        $user = User::with(['profile', 'receivedReports'])->where('id', $userId)->first();
        return $this->response->item($user, new \App\Transformers\UserTransformer);
    }

    public function moderateUser($userId, Request $request)
    {

        $v = Validator::make($request->all(), [
            'action' => 'required|in:approve,reject,remove,ban,unban'
        ]);

        if ($v->fails()) throw new \Dingo\Api\Exception\UpdateResourceFailedException('Could not set status for ride request.', $v->errors()->all());

        $user = User::find($userId);
        switch($request->input('action')) {
            case 'approve':
                $user->approved_at = Carbon::now();
                $user->save();
                break;
            case 'reject':
                $user->rejected_at = Carbon::now();
                $user->save();
                break;
            case 'reject':
                $user->rejected_at = Carbon::now();
                $user->save();
                break;
            case 'ban':
                $user->banned_at = Carbon::now();
                $user->save();
                break;
            case 'unban':
                $user->banned_at = null;
                $user->save();
                break;
            case 'remove':
                $user->delete();
                break;
        }
        
        
        return $this->response->noContent();
    }

}
