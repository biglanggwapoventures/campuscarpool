<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;
use JWTAuth;

class UserRequirementsRequest extends FormRequest
{

    // use Helpers;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $data = ['school_id' => 'required|image|max:5000'];

        if($user->isDriver()){
            $data += [
                'vehicle_type' => 'required|in:CAR,MOTORCYCLE',
                'vehicle_model' => 'required',
                'vehicle_plate_number' => 'required',
                'drivers_license' => 'required|image|max:5000'
            ];
        }

        return $data;
    }
}
