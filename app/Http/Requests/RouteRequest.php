<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;
use JWTAuth;
use Illuminate\Validation\Factory as ValidationFactory;
use Carbon\Carbon;

class RouteRequest extends FormRequest
{
    public function __construct(ValidationFactory $validationFactory)
    {
        $validationFactory->extend('gt3hrs',
            function ($attribute, $value, $parameters) {
                $departureDatetime = Carbon::createFromFormat('Y-m-d H:i', $value);
                return $departureDatetime->diffInHours(Carbon::now()) >= 3;
            },
            'The departure date and time must be at least 3 hours from now.'
        );
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = JWTAuth::parseToken()->authenticate();
        return $user->isDriver();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'type' => 'required|in:CAMPUS,HOME',
            'place.id' => 'required',
            'place.latitude' => 'required',
            'place.longitude' => 'required',
            'place.formatted_address' => 'required',
            'departure_datetime' => 'required|date_format:"Y-m-d H:i"|gt3hrs',
            'fare_contribution' => 'required|numeric',
            'max_passenger' => 'required|integer|max:5|min:1',
            'route_index' => 'required|integer'
        ];

        return $rules;
    }

    public function messages(){
        return [
            'place.id.required' => 'Do not leave this field empty!'
        ];
    }
}
