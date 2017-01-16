<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RideRequest extends Model
{
    protected $table = 'ride_requests';

    protected $fillable = [
        'driver_route_id',
        'commuter_id',
        'num_seats',
        'pickup_latitude',
        'pickup_longitude',
        'dropoff_latitude',
        'dropoff_longitude',
        'accepted'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'rejected' => 'boolean',
        'accepted' => 'boolean',
    ];

    public function commuter()
    {
        return $this->belongsTo('App\User', 'commuter_id');
    }
    
    public function driverRoute()
    {
        return $this->belongsTo('App\DriverRoute');
    }

    public function isActive()
    {
        if($this->rejected) return false;
        return $this->driverRoute->departure_datetime->isFuture();
    }

    public function scopeAccepted($query)
    {
        return $query->where('accepted', 1);
    }

    public function scopeLatest()
    {
        return $this->orderBy('created_at', 'DESC');
    }
}
