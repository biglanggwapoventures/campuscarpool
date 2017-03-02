<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class RideRequest extends Model
{
    use SoftDeletes;
    protected $table = 'ride_requests';

    protected $fillable = [
        'driver_route_id',
        'commuter_id',
        'num_seats',
        // 'pickup_latitude',
        // 'pickup_longitude',
        // 'dropoff_latitude',
        // 'dropoff_longitude',
        'location_latitude',
        'location_longitude',
        'location_address',
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
        if($this->rejected || $this->cancelled_at) return false;
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

     public function scopeActive($query)
    {
        return $query
            ->where('rejected', 0)
            ->whereNull('cancelled_at');
    }

    public function scopeCurrentDay($query)
    {
        return $query->whereRaw('DATE(created_at) = CURDATE()');
    }

    public function getStatus()
    {
        if($this->accepted) return 'accepted';
        if($this->rejected) return 'rejected';
        return 'pending';
    }

    public function isDone()
    {
        return $this->driverRoute->departure_datetime->lt(Carbon::now());
    }

    
}
