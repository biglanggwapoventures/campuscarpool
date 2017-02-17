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

        $schoolIdPath = $request->file('school_id')->store("school_id/{$user->id}", 'public');
        $user->school_id_filename = "storage/{$schoolIdPath}";
        $user->save();
        
        if($user->isDriver()){

            $input = $request->only([
                'vehicle_type',
                'vehicle_model',
                'vehicle_plate_number'
            ]);

            $driversLicenseFilename = $request->file('drivers_license')->store("drivers_license/{$this->user->id}", 'public');
            $input['drivers_license_filename'] = "storage/{$driversLicenseFilename}";

            $user->profile()->save(new DriverProfile($input));

        }
        return $this->response->noContent();
    }
}
