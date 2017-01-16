<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DriverProfile extends Model
{
    protected $table = 'driver_profile';

    protected $fillable = [
        'user_id',
        'vehicle_type',
        'vehicle_model',
        'vehicle_plate_number',
        'drivers_license_filename'
    ];

    public function driver()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
