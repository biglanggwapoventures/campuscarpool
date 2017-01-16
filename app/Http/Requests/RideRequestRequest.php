<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;
use Illuminate\Validation\Factory as ValidationFactory;

class RideRequestRequest extends FormRequest
{

    public function __construct(ValidationFactory $validationFactory)
    {
        $validationFactory->extend('coordinates',
            function ($attribute, $value, $parameters) {
                $coordinates = explode(',', $value);
                return count(array_filter($coordinates, 'is_numeric')) === 2;
            },
            'The :name must contain valid coordinates.'
        );
    }

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
        $data = [
            'driver_route_id' => 'required|exists:driver_routes,id',
            'num_seats' => 'integer|min:1|max:5',
            'message' => 'required',
            'pickup' => 'required|coordinates',
            'dropoff' => 'required|coordinates',
        ];
        
        return $data;
    }

    public function messages ()
    {
        return [
            'dropoff.latitude.required' => 'Please select a provide a dropoff point.',
            'dropoff.longitude.required' => 'Please select a provide a dropoff point.',
            'pickup.latitude.required' => 'Please select a provide a pickup point.',
            'pickup.longitude.required' => 'Please select a provide a pickup point.'
        ];
    }

    public function attributes()
    {
        return[
            'dropoff.latitude' => 'dropoff', 
            'dropoff.longitude' => 'dropoff', 
            'pickup.latitude' => 'pickup', 
            'pickup.longitude' => 'pickup', 
        ];

    }
}
