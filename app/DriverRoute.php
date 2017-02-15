<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;


class DriverRoute extends Model
{
    use SoftDeletes;

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'departure_datetime'
    ];

    protected $fillable = [
        'type',
        'departure_datetime',
        'route_index',
        'place_id',
        'place_formatted_address',
        'place_latitude',
        'place_longitude',
        'max_passenger',
        'fare_contribution',
        'additional_details',
        'created_by'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    protected $casts = [
        'ratings_done' => 'boolean'
    ];

    public function scopeActive($query)
    {
        return $query->whereRaw('departure_datetime > NOW()');
    }

    public function driver()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function rideRequests()
    {
        return $this->hasMany('App\RideRequest', 'driver_route_id');
    }

    public function acceptedRequests()
    {
        return $this->rideRequests()->accepted();
    }
    
    public function numSeatsTaken()
    {
        return $this->acceptedRequests()->sum('num_seats');
    }

    public function seatsFull()
    {
        return $this->numSeatsTaken() >= $this->max_passenger;
    }

    public function hasAvailableSeats()
    {
        return $this->numSeatsTaken() < $this->max_passenger;
    }


    public function isDone()
    {
        return $this->departure_datetime->lt(Carbon::now());
    }
    
    
}
