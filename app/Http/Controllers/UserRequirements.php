<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequirementsRequest;
use App\DriverProfile;


class UserRequirements extends Controller
{
    public function save(UserRequirementsRequest $request)
    {
        $user = $this->auth->user();

        $schoolId = $request->file('school_id')->store("images/{$user->id}/school_id", 'public');
        $user->school_id_filename = $schoolId;
        $user->save();
        
        if($user->isDriver()){

            $input = $request->only([
                'vehicle_type',
                'vehicle_model',
                'vehicle_plate_number'
            ]);

            $input['drivers_license_filename'] = $request->file('drivers_license')->store("images/{$this->user->id}/school_id", 'public');

            $user->profile()->save(new DriverProfile($input));

        }
        return $this->response->noContent();
    }
}
