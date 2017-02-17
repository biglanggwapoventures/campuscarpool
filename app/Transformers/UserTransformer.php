<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        $requirements = [
            'display_photo' => asset($user->display_photo),
            'school_id' => asset($user->school_id_filename),
        ];

        if($user->rejected_at){
            $code = 2;
            $statusText = 'Rejected';
            $actionDate =  $user->rejected_at->format('m/d/Y h:i A');
        }elseif($user->approved_at && !$user->banned_at){
            $code = 1;
            $statusText = 'Approved';
            $actionDate =  $user->approved_at->format('m/d/Y h:i A');
        }elseif($user->approved_at && $user->banned_at){
            $code = 3;
            $statusText = 'Banned';
            $actionDate =  $user->banned_at->format('m/d/Y h:i A');
        }
        else{
            $code = 0;
            $statusText = 'Pending';
        }

        $status = [
            'code' => $code,
            'label' => $statusText,
            'date' => isset($actionDate) ? $actionDate : null
        ];

        if($user->isDriver()){
            $requirements  +=  [
               'drivers_license' => asset($user->profile->drivers_license_filename),
               'vehicle_model' => $user->profile->vehicle_model,
               'plate_number' => $user->profile->vehicle_plate_number,
            ];
        }

        return [
                
            'id' => (int)$user->id,
            'email' => $user->email,
            'account_type' => ucfirst(strtolower($user->role)),
            'name' => [
                'firstname' => $user->firstname,
                'lastname' =>  $user->lastname,
                'fullname' => $user->fullName(),
            ],
            'id_number' => $user->id_number,
            'requirements' => $requirements,
            'reports' => $user->receivedReports ?: [],
            'banned' => (bool)$user->banned_at,
            'joined_at' => $user->created_at->format('m/d/Y h:i A'),
            'status' => $status
	    ];
    }

}